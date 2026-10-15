using System.Security.Claims;
using System.Text.Json;
using FluentValidation;
using FluentValidation.Results;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Moq;
using Tasuku.Controllers.Users;
using Tasuku.DTO.Users;
using Tasuku.Requests.Users;
using Tasuku.Services.Users;

namespace Tasuku.Tests.Controllers;

public class UserControllerUnitTests
{
    private readonly UserController _controller;

    private readonly DefaultHttpContext _httpContext = new();

    private readonly Mock<IUserService> _service = new();

    private readonly Mock<IValidator<SignInRequest>> _signInRequestValidator = new();

    public UserControllerUnitTests()
    {
        _controller = new UserController(_service.Object, _signInRequestValidator.Object)
        {
            ControllerContext = new ControllerContext { HttpContext = _httpContext }
        };
    }

    private static SignInRequest CreateRequest()
    {
        return new SignInRequest();
    }

    private static UserResponse CreateUser()
    {
        return new UserResponse(Guid.NewGuid(), "test", "test@test.test");
    }

    private void SetupValidation(SignInRequest request, ValidationResult result)
    {
        _signInRequestValidator.Setup(v => v.ValidateAsync(request, It.IsAny<CancellationToken>()))
            .ReturnsAsync(result);
    }

    private void SetupAuthenticatedUser(string claimType, string value)
    {
        _httpContext.User = new ClaimsPrincipal(new ClaimsIdentity([new Claim(claimType, value)], "test"));
    }

    private static string FakeJwt()
    {
        return
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIwMDAwMDAwMC0wMDAwLTAwMDAtMDAwMC0wMDAwMDAwMDAwMDAiLCJleHAiOjAsImlzcyI6IlRhc3VrdSJ9.QLnXhvn6xjOclnfi4KDPUsJOKTx8V8HNqQUtXZxgxxo";
    }

    [Fact]
    public async Task SignIn_ShouldReturnValidationProblem_WhenRequestIsInvalid()
    {
        var request = CreateRequest();

        SetupValidation(request, new ValidationResult([new ValidationFailure("Email", "Email is required.")]));

        var response = await _controller.SignIn(request);

        var objectResult = Assert.IsType<ObjectResult>(response, false);

        var problem = Assert.IsType<ValidationProblemDetails>(objectResult.Value);

        Assert.Contains("Email", problem.Errors.Keys);

        _service.Verify(s => s.SignIn(It.IsAny<SignInRequest>()), Times.Never);
    }

    [Fact]
    public async Task SignIn_ShouldReturnUnauthorized_WhenCredentialsAreInvalid()
    {
        var request = CreateRequest();

        SetupValidation(request, new ValidationResult());

        _service.Setup(s => s.SignIn(request)).ReturnsAsync((string?)null);

        var response = await _controller.SignIn(request);

        var unauthorized = Assert.IsType<UnauthorizedObjectResult>(response);

        Assert.False(_httpContext.Response.Headers.ContainsKey("Set-Cookie"));

        var json = JsonSerializer.Serialize(unauthorized.Value);

        Assert.Equal("""{"message":"Invalid credentials."}""", json);
    }

    [Fact]
    public async Task SignIn_ShouldReturnNoContentAndSetCookie_WhenCredentialsAreValid()
    {
        var request = CreateRequest();

        SetupValidation(request, new ValidationResult());

        _service.Setup(s => s.SignIn(request)).ReturnsAsync(FakeJwt());

        var response = await _controller.SignIn(request);

        Assert.IsType<NoContentResult>(response);

        var cookie = _httpContext.Response.Headers.SetCookie.ToString();

        Assert.Contains($"jwt={FakeJwt()}", cookie);

        Assert.Contains("httponly", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("secure", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("samesite=strict", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("expires=", cookie, StringComparison.OrdinalIgnoreCase);
    }

    [Fact]
    public void SignOut_ShouldReturnNoContentAndExpireCookie()
    {
        var response = _controller.SignOut();

        Assert.IsType<NoContentResult>(response);

        var cookie = _httpContext.Response.Headers.SetCookie.ToString();

        Assert.Contains("jwt=;", cookie);

        Assert.Contains("expires=Thu, 01 Jan 1970", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("path=/", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("secure", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("httponly", cookie, StringComparison.OrdinalIgnoreCase);

        Assert.Contains("samesite=strict", cookie, StringComparison.OrdinalIgnoreCase);
    }

    [Fact]
    public async Task Me_ShouldReturnUnauthorized_WhenNoIdentifierClaimExists()
    {
        var response = await _controller.Me();

        Assert.IsType<UnauthorizedResult>(response.Result);

        _service.Verify(s => s.GetById(It.IsAny<Guid>()), Times.Never);
    }

    [Fact]
    public async Task Me_ShouldReturnNotFound_WhenUserDoesNotExist()
    {
        var userId = Guid.NewGuid();

        SetupAuthenticatedUser(ClaimTypes.NameIdentifier, userId.ToString());

        _service.Setup(s => s.GetById(userId)).ReturnsAsync((UserResponse?)null);

        var response = await _controller.Me();

        Assert.IsType<NotFoundResult>(response.Result);
    }

    [Fact]
    public async Task Me_ShouldReturnOkWithUser_WhenNameIdentifierClaimIsPresent()
    {
        var userId = Guid.NewGuid();

        var user = CreateUser();

        SetupAuthenticatedUser(ClaimTypes.NameIdentifier, userId.ToString());

        _service.Setup(s => s.GetById(userId)).ReturnsAsync(user);

        var response = await _controller.Me();

        var ok = Assert.IsType<OkObjectResult>(response.Result);

        Assert.Same(user, ok.Value);
    }
}
