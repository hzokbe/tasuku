using System.Net;
using System.Net.Http.Json;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Mvc.Testing;
using Microsoft.Extensions.DependencyInjection;
using Microsoft.Extensions.DependencyInjection.Extensions;
using NSubstitute;
using Tasuku.DTO.Users;
using Tasuku.Models;
using Tasuku.Repositories.Users;

namespace Tasuku.Tests.Controllers;

public class TasukuFactory : WebApplicationFactory<Program>
{
    public IUserRepository Repository { get; } = Substitute.For<IUserRepository>();

    protected override void ConfigureWebHost(IWebHostBuilder builder)
    {
        builder.UseSetting("Jwt:Key",
            "96b1b1e74283990edc697bf6b0729fa6b4181aef490c39558cc6190521e232f2382d8cf6c508934192c5f57a41fe8709a8c1e493fc7db836fb46eb068c77f975");

        builder.UseSetting("Jwt:Issuer", "Tasuku");

        builder.UseSetting("Jwt:ExpiresInMinutes", "60");

        builder.ConfigureServices(services =>
        {
            services.RemoveAll<IUserRepository>();
            services.AddSingleton(Repository);
        });
    }

    public HttpClient CreateSecureClient()
    {
        return CreateClient(new WebApplicationFactoryClientOptions
        {
            BaseAddress = new Uri("https://localhost"),
            HandleCookies = true,
            AllowAutoRedirect = false
        });
    }
}

public class UserControllerIntegrationTests(TasukuFactory factory) : IClassFixture<TasukuFactory>
{
    private const string Password = "test";

    private User SeedUser()
    {
        var user = new User
        {
            Id = Guid.NewGuid(),
            Username = "test",
            Email = "test@test.com",
            PasswordHash = BCrypt.Net.BCrypt.HashPassword(Password)
        };

        factory.Repository.GetByEmailAsync(user.Email).Returns(user);

        factory.Repository.GetByIdAsync(user.Id).Returns(user);

        return user;
    }

    [Fact]
    public async Task SignIn_WithValidCredentials_ReturnsNoContentAndSetsCookie()
    {
        var user = SeedUser();

        var client = factory.CreateSecureClient();

        var response = await client.PostAsJsonAsync("/sign-in", new { user.Email, Password });

        Assert.Equal(HttpStatusCode.NoContent, response.StatusCode);

        Assert.Contains(response.Headers.GetValues("Set-Cookie"), c => c.StartsWith("jwt=") && c.Contains("httponly"));
    }

    [Fact]
    public async Task SignIn_WithWrongPassword_ReturnsUnauthorized()
    {
        var user = SeedUser();

        var client = factory.CreateSecureClient();

        var response = await client.PostAsJsonAsync("/sign-in", new { user.Email, Password = "test!123" });

        Assert.Equal(HttpStatusCode.Unauthorized, response.StatusCode);
    }

    [Fact]
    public async Task SignIn_WithInvalidEmail_ReturnsBadRequest()
    {
        var client = factory.CreateSecureClient();

        var response = await client.PostAsJsonAsync("/sign-in", new { Email = "test", Password });

        Assert.Equal(HttpStatusCode.BadRequest, response.StatusCode);
    }

    [Fact]
    public async Task Me_WithoutAuthentication_ReturnsUnauthorized()
    {
        var client = factory.CreateSecureClient();

        var response = await client.GetAsync("/me");

        Assert.Equal(HttpStatusCode.Unauthorized, response.StatusCode);
    }

    [Fact]
    public async Task Me_AfterSignIn_ReturnsCurrentUser()
    {
        var user = SeedUser();

        var client = factory.CreateSecureClient();

        await client.PostAsJsonAsync("/sign-in", new { user.Email, Password });

        var response = await client.GetAsync("/me");

        var body = await response.Content.ReadFromJsonAsync<UserResponse>();

        Assert.Equal(HttpStatusCode.OK, response.StatusCode);

        Assert.Equal(user.Id, body!.Id);

        Assert.Equal(user.Email, body.Email);
    }

    [Fact]
    public async Task SignOut_AfterSignIn_RemovesAccess()
    {
        var user = SeedUser();

        var client = factory.CreateSecureClient();

        await client.PostAsJsonAsync("/sign-in", new { user.Email, Password });

        var signOut = await client.PostAsync("/sign-out", null);

        var me = await client.GetAsync("/me");

        Assert.Equal(HttpStatusCode.NoContent, signOut.StatusCode);

        Assert.Equal(HttpStatusCode.Unauthorized, me.StatusCode);
    }
}
