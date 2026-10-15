using FluentValidation;
using Tasuku.Requests.Users;

namespace Tasuku.Validators.Users;

public class SignInRequestValidator : AbstractValidator<SignInRequest>
{
    public SignInRequestValidator()
    {
        RuleFor(request => request.Email)
            .Cascade(CascadeMode.Stop)
            .NotEmpty()
            .WithMessage("E-mail is required.")
            .MaximumLength(254)
            .WithMessage("E-mail must be at most 254 characters.")
            .EmailAddress()
            .WithMessage("E-mail is not valid.");
    }
}
