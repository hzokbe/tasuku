using System.IdentityModel.Tokens.Jwt;
using System.Text;
using Microsoft.Extensions.Configuration;
using Microsoft.IdentityModel.Tokens;
using Tasuku.Services.Tokens;

namespace Tasuku.Tests.Services;

public class TokenServiceUnitTests
{
    private const string Key =
        "96b1b1e74283990edc697bf6b0729fa6b4181aef490c39558cc6190521e232f2382d8cf6c508934192c5f57a41fe8709a8c1e493fc7db836fb46eb068c77f975";

    private const string Issuer = "Tasuku";

    private const int ExpiresInMinutes = 60;

    private const string Sub = "abe6c95e-598e-4caf-8ce0-317ce420f5b3";

    private static IConfiguration BuildConfig(
        string? key = Key,
        string? issuer = Issuer,
        string? expiresInMinutes = "60"
    )
    {
        var values = new Dictionary<string, string?>
        {
            ["Jwt:Key"] = key,
            ["Jwt:Issuer"] = issuer,
            ["Jwt:ExpiresInMinutes"] = expiresInMinutes
        };

        return new ConfigurationBuilder().AddInMemoryCollection(values).Build();
    }

    private static TokenService CreateService(IConfiguration? config = null)
    {
        return new TokenService(config ?? BuildConfig());
    }

    [Fact]
    public void GenerateToken_ShouldReturnNonEmptyJwt_WhenConfigIsValid()
    {
        var token = CreateService().GenerateToken(Sub);

        Assert.False(string.IsNullOrWhiteSpace(token));

        Assert.Equal(3, token.Split('.').Length);
    }

    [Fact]
    public void GenerateToken_ShouldContainSubClaim_WhenSubIsProvided()
    {
        var token = CreateService().GenerateToken(Sub);

        var jwt = new JwtSecurityTokenHandler().ReadJwtToken(token);

        Assert.Equal(Sub, jwt.Subject);
    }

    [Fact]
    public void GenerateToken_ShouldSetIssuer_WhenConfigIsValid()
    {
        var token = CreateService().GenerateToken(Sub);

        var jwt = new JwtSecurityTokenHandler().ReadJwtToken(token);

        Assert.Equal(Issuer, jwt.Issuer);
    }

    [Fact]
    public void GenerateToken_ShouldSetExpirationAccordingToConfig_WhenExpiresInMinutesIsConfigured()
    {
        var before = DateTime.UtcNow;

        var token = CreateService().GenerateToken(Sub);

        var after = DateTime.UtcNow;

        var jwt = new JwtSecurityTokenHandler().ReadJwtToken(token);

        Assert.InRange(
            jwt.ValidTo,
            before.AddMinutes(ExpiresInMinutes).AddSeconds(-2),
            after.AddMinutes(ExpiresInMinutes).AddSeconds(2)
        );
    }

    [Fact]
    public void GenerateToken_ShouldProduceTokenValidWithSameKey_WhenValidatedWithConfiguredKey()
    {
        var token = CreateService().GenerateToken(Sub);

        var parameters = new TokenValidationParameters
        {
            ValidateIssuer = true,
            ValidIssuer = Issuer,
            ValidateAudience = false,
            ValidateLifetime = true,
            ValidateIssuerSigningKey = true,
            IssuerSigningKey = new SymmetricSecurityKey(Encoding.UTF8.GetBytes(Key))
        };

        var principal = new JwtSecurityTokenHandler().ValidateToken(token, parameters, out var validated);

        Assert.NotNull(principal);

        Assert.IsType<JwtSecurityToken>(validated);
    }

    [Fact]
    public void GenerateToken_ShouldProduceTokenInvalidWithDifferentKey_WhenValidatedWithAnotherKey()
    {
        var token = CreateService().GenerateToken(Sub);

        var parameters = new TokenValidationParameters
        {
            ValidateIssuer = false,
            ValidateAudience = false,
            ValidateLifetime = false,
            ValidateIssuerSigningKey = true,
            IssuerSigningKey = new SymmetricSecurityKey(
            [
                .. "ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff"u8
            ])
        };

        Assert.Throws<SecurityTokenSignatureKeyNotFoundException>(() =>
            new JwtSecurityTokenHandler().ValidateToken(token, parameters, out _));
    }

    [Fact]
    public void GenerateToken_ShouldProduceDifferentTokensForDifferentSubs_WhenSubsDiffer()
    {
        var service = CreateService();

        var first = service.GenerateToken(Sub);

        var second = service.GenerateToken("c433ed82-a4d0-4f22-affa-b0f25ed8ab2e");

        Assert.NotEqual(first, second);
    }
}
