using Microsoft.EntityFrameworkCore;
using Tasuku.Data;
using Tasuku.Models;
using Tasuku.Repositories.Users;

namespace Tasuku.Tests.Repositories;

public class UserRepositoryUnitTests
{
    private static DbContextOptions<AppDbContext> CreateOptions()
    {
        return new DbContextOptionsBuilder<AppDbContext>().UseInMemoryDatabase(Guid.NewGuid().ToString()).Options;
    }

    private static async Task SeedAsync(DbContextOptions<AppDbContext> options, params User[] users)
    {
        await using var context = new AppDbContext(options);

        context.Users.AddRange(users);

        await context.SaveChangesAsync();
    }

    private static User CreateUser()
    {
        return new User
        {
            Username = "test",
            Email = "test@test.test",
            PasswordHash = "$2a$12$1pPdiONoVAvdjAy.3po9ZujgikhR.F9mRytWJtAxQYOlXUB0vYcqu"
        };
    }

    [Fact]
    public async Task GetByIdAsync_ShouldReturnNull_WhenUserDoesNotExist()
    {
        var options = CreateOptions();

        await using var context = new AppDbContext(options);

        var repository = new UserRepository(context);

        var user = await repository.GetByIdAsync(Guid.NewGuid());

        Assert.Null(user);
    }

    [Fact]
    public async Task GetByIdAsync_ShouldReturnUser_WhenUserExists()
    {
        var options = CreateOptions();

        var user = CreateUser();

        await SeedAsync(options, user);

        await using var context = new AppDbContext(options);

        var repository = new UserRepository(context);

        var result = await repository.GetByIdAsync(user.Id);

        Assert.NotNull(result);

        Assert.Equal(user.Id, result.Id);

        Assert.Equal(user.Email, result.Email);

        Assert.Equal(user.PasswordHash, result.PasswordHash);
    }

    [Fact]
    public async Task GetByEmailAsync_ShouldReturnNull_WhenUserDoesNotExist()
    {
        var options = CreateOptions();

        await using var context = new AppDbContext(options);

        var repository = new UserRepository(context);

        var user = await repository.GetByEmailAsync("test@test.test");

        Assert.Null(user);
    }

    [Fact]
    public async Task GetByEmailAsync_ShouldReturnUser_WhenUserExists()
    {
        var options = CreateOptions();

        var user = CreateUser();

        await SeedAsync(options, user);

        await using var context = new AppDbContext(options);

        var repository = new UserRepository(context);

        var result = await repository.GetByEmailAsync("test@test.test");

        Assert.NotNull(result);

        Assert.Equal(user.Id, result.Id);

        Assert.Equal(user.Email, result.Email);

        Assert.Equal(user.PasswordHash, result.PasswordHash);
    }
}
