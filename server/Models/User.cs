namespace Tasuku.Models;

public class User
{
    public Guid Id { get; init; } = Guid.NewGuid();

    public required string Username { get; set; }

    public required string Email { get; set; }

    public required string PasswordHash { get; set; }
}
