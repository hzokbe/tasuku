using Tasuku.DTO.Users;
using Tasuku.Models;
using Tasuku.Repositories.Users;
using Tasuku.Requests.Users;
using Tasuku.Services.Passwords;
using Tasuku.Services.Tokens;

namespace Tasuku.Services.Users;

public class UserService(
    IUserRepository repository,
    IPasswordHasherService passwordHasherService,
    ITokenService tokenService)
    : IUserService
{
    public async Task<string?> SignIn(SignInRequest request)
    {
        var user = await repository.GetByEmailAsync(request.Email);

        if (user == null) return null;

        var passwordHash = user.PasswordHash;

        return passwordHasherService.Verify(request.Password, passwordHash)
            ? tokenService.GenerateToken(user.Id.ToString())
            : null;
    }

    public async Task<UserResponse?> GetById(Guid id)
    {
        var user = await repository.GetByIdAsync(id);

        return user == null ? null : ToResponse(user);
    }

    private static UserResponse ToResponse(User user)
    {
        return new UserResponse(user.Id, user.Username, user.Email);
    }
}
