namespace Tasuku.Services.Tokens;

public interface ITokenService
{
    public string GenerateToken(string sub);
}
