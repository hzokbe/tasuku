using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using FluentValidation;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Tasuku.DTO.Users;
using Tasuku.Requests.Users;
using Tasuku.Services.Users;

namespace Tasuku.Controllers.Users;

[ApiController]
public class UserController(IUserService service, IValidator<SignInRequest> signInRequestValidator) : ControllerBase
{
    [HttpPost("/sign-in")]
    public async Task<IActionResult> SignIn(SignInRequest request)
    {
        var result = await signInRequestValidator.ValidateAsync(request);

        if (!result.IsValid) return ValidationProblem(new ValidationProblemDetails(result.ToDictionary()));

        var token = await service.SignIn(request);

        if (token is null) return Unauthorized(new { message = "Invalid credentials." });

        Response.Cookies.Append("jwt", token, new CookieOptions
        {
            HttpOnly = true,
            Secure = true,
            SameSite = SameSiteMode.Strict,
            Expires = DateTimeOffset.UtcNow.AddMinutes(60),
            Path = "/"
        });

        return NoContent();
    }

    [Authorize]
    [HttpPost("/sign-out")]
    public new IActionResult SignOut()
    {
        Response.Cookies.Delete("jwt", new CookieOptions
        {
            HttpOnly = true,
            Secure = true,
            SameSite = SameSiteMode.Strict,
            Path = "/"
        });

        return NoContent();
    }

    [Authorize]
    [HttpGet("/me")]
    public async Task<ActionResult<UserResponse>> Me()
    {
        var value = User.FindFirstValue(ClaimTypes.NameIdentifier) ?? User.FindFirstValue(JwtRegisteredClaimNames.Sub);

        if (!Guid.TryParse(value, out var userId)) return Unauthorized();

        var user = await service.GetById(userId);

        return user is null ? NotFound() : Ok(user);
    }
}
