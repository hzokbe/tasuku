using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using Tasuku.Models;

namespace Tasuku.Data.Configurations;

public class UserConfiguration : IEntityTypeConfiguration<User>
{
    public void Configure(EntityTypeBuilder<User> builder)
    {
        builder.HasKey(u => u.Id);

        builder.Property(u => u.Username).HasMaxLength(16).IsRequired();

        builder.Property(u => u.Email).HasMaxLength(254).IsRequired();

        builder.Property(u => u.PasswordHash).HasMaxLength(60).IsRequired();

        builder.HasIndex(u => u.Username).IsUnique();

        builder.HasIndex(u => u.Email).IsUnique();
    }
}
