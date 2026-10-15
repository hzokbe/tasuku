namespace Tasuku.Requests.Users;

public record SignInRequest
{
    public string Email
    {
        get;
        init => field = value.Trim().ToLowerInvariant();
    } = string.Empty;

    public string Password { get; init; } = string.Empty;
}
