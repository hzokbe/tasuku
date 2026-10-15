using Tasuku.Services.Passwords;

namespace Tasuku.Tests.Services;

public class PasswordHasherServiceUnitTests
{
    private const string Password = "test";

    private readonly PasswordHasherService _service = new();

    private static string Hash(string password)
    {
        return BCrypt.Net.BCrypt.HashPassword(password, 12);
    }

    [Fact]
    public void Verify_ShouldReturnTrue_WhenPasswordMatchesHash()
    {
        var hash = Hash(Password);

        var result = _service.Verify(Password, hash);

        Assert.True(result);
    }

    [Fact]
    public void Verify_ShouldReturnFalse_WhenPasswordDoesNotMatchHash()
    {
        var hash = Hash(Password);

        var result = _service.Verify("test!123", hash);

        Assert.False(result);
    }

    [Fact]
    public void Verify_ShouldBeCaseSensitive()
    {
        var hash = Hash(Password);

        var result = _service.Verify(Password.ToUpperInvariant(), hash);

        Assert.False(result);
    }

    [Fact]
    public void Verify_ShouldReturnFalse_WhenPasswordIsEmpty()
    {
        var hash = Hash(Password);

        var result = _service.Verify(string.Empty, hash);

        Assert.False(result);
    }
}
