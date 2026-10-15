using Microsoft.EntityFrameworkCore;

namespace Tasuku.Data;

public class AppDbContext(DbContextOptions<AppDbContext> options) : DbContext(options);
