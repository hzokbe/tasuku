namespace Tasuku.DTO.Users;

public record UserResponse(
    Guid Id,
    string Username,
    string Email
);
