using Tasuku.Models;

namespace Tasuku.Repositories.Users;

public interface IUserRepository
{
    public Task<User?> GetByIdAsync(Guid id);

    public Task<User?> GetByEmailAsync(string email);
}
