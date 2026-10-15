namespace Tasuku.Services.Passwords;

public interface IPasswordHasherService
{
    public bool Verify(string hash, string password);
}
