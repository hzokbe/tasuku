FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build

WORKDIR /src

COPY Tasuku.csproj ./

RUN dotnet restore Tasuku.csproj

COPY . .

RUN dotnet publish Tasuku.csproj -c Release -o /out --no-restore

FROM mcr.microsoft.com/dotnet/aspnet:10.0

WORKDIR /app

COPY --from=build /out .

ENTRYPOINT ["dotnet", "Tasuku.dll"]
