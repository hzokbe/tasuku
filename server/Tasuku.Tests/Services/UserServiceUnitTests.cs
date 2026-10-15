using Moq;
using Tasuku.Models;
using Tasuku.Repositories.Users;
using Tasuku.Requests.Users;
using Tasuku.Services.Passwords;
using Tasuku.Services.Tokens;
using Tasuku.Services.Users;

namespace Tasuku.Tests.Services;

public class UserServiceUnitTests
{
    private readonly Mock<IPasswordHasherService> _passwordHasher = new();

    private readonly Mock<IUserRepository> _repository = new();

    private readonly UserService _service;

    private readonly Mock<ITokenService> _tokenService = new();

    public UserServiceUnitTests()
    {
        _service = new UserService(_repository.Object, _passwordHasher.Object, _tokenService.Object);
    }

    private static SignInRequest CreateSignInRequest()
    {
        return new SignInRequest
        {
            Email = "test@test.test",
            Password = "test"
        };
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

    private static string FakeJwt()
    {
        return
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIwMDAwMDAwMC0wMDAwLTAwMDAtMDAwMC0wMDAwMDAwMDAwMDAiLCJleHAiOjAsImlzcyI6IlRhc3VrdSJ9.QLnXhvn6xjOclnfi4KDPUsJOKTx8V8HNqQUtXZxgxxo";
    }

    [Fact]
    public async Task SignIn_ShouldReturnNull_WhenUserDoesNotExist()
    {
        _repository.Setup(r => r.GetByEmailAsync(It.IsAny<string>())).ReturnsAsync((User?)null);

        var jwt = await _service.SignIn(CreateSignInRequest());

        Assert.Null(jwt);

        _repository.Verify(r => r.GetByEmailAsync(It.IsAny<string>()), Times.Once);

        _passwordHasher.Verify(p => p.Verify(It.IsAny<string>(), It.IsAny<string>()), Times.Never);
    }

    [Fact]
    public async Task SignIn_ShouldReturnJwt_WhenUserExists()
    {
        _repository.Setup(r => r.GetByEmailAsync(It.IsAny<string>())).ReturnsAsync(CreateUser());

        _passwordHasher.Setup(p => p.Verify(It.IsAny<string>(), It.IsAny<string>())).Returns(true);

        _tokenService.Setup(s => s.GenerateToken(It.IsAny<string>())).Returns(FakeJwt());

        var jwt = await _service.SignIn(CreateSignInRequest());

        Assert.NotNull(jwt);

        Assert.Equal(FakeJwt(), jwt);

        _repository.Verify(r => r.GetByEmailAsync(It.IsAny<string>()), Times.Once);

        _passwordHasher.Verify(p => p.Verify(It.IsAny<string>(), It.IsAny<string>()), Times.Once);
    }

    [Fact]
    public async Task GetById_ShouldReturnNull_WhenUserDoesNotExist()
    {
        _repository.Setup(r => r.GetByIdAsync(It.IsAny<Guid>())).ReturnsAsync((User?)null);

        var user = await _service.GetById(Guid.NewGuid());

        Assert.Null(user);
    }

    [Fact]
    public async Task GetById_ShouldReturnUser_WhenUserExists()
    {
        var user = CreateUser();

        _repository.Setup(r => r.GetByIdAsync(It.IsAny<Guid>())).ReturnsAsync(user);

        var result = await _service.GetById(user.Id);

        Assert.NotNull(user);

        Assert.Equal(user.Id, result!.Id);

        Assert.Equal(user.Username, result.Username);

        Assert.Equal(user.Email, result.Email);
    }
}
