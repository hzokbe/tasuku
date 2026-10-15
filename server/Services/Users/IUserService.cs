using Tasuku.DTO.Users;
using Tasuku.Requests.Users;

namespace Tasuku.Services.Users;

public interface IUserService
{
    public Task<string?> SignIn(SignInRequest request);

    public Task<UserResponse?> GetById(Guid id);
}
