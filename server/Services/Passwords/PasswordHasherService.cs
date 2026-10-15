namespace Tasuku.Services.Passwords;

public class PasswordHasherService : IPasswordHasherService
{
    public bool Verify(string password, string hash)
    {
        return BCrypt.Net.BCrypt.Verify(password, hash);
    }
}
