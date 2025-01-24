# Forge Framework

A lightweight, flexible, and modular PHP framework designed for long-term maintainability, scalability, and simplicity. This framework prioritizes clean architecture, developer experience, and best practices while avoiding unnecessary dependencies and coupling.

## Description

This framework provides a robust foundation for building modern PHP applications by combining:
- Flexibility to adapt to various project structures.
- Simplicity in design, ensuring ease of understanding and use.
- Extensibility to empower developers with tools and features that align with their specific needs.

Whether you're working on a small project or a large-scale application, this framework offers a foundation you can trust for decades.

## Problem the Framework Solves

Frameworks often:
- Include unused features, adding unnecessary bloat.
- Have tightly coupled components, making it difficult to customize or replace functionality.
- Require constant updates to remain secure and compatible.

This framework addresses these issues by:
- Providing only essential tools and features while remaining easily extensible.
- Following SOLID principles and separation of concerns, allowing developers to replace or extend components as needed.
- Ensuring the core remains stable, reliable, and independent of external libraries or frameworks.

## Features

### Core Philosophy
- **Minimal Dependencies**: Avoid unnecessary libraries to reduce bloat and security risks.
- **Modular Design**: Encourages a clean and organized codebase.
- **Flexibility**: Supports multiple project structures like default, modular, and DDD.

### Key Features
- **Custom Lightweight Router**: A built-in router for handling routes efficiently.
- **View Engine**: Ships with a simple, extendable view engine that supports IntelliSense.
- **Seeding and Migrations**: Tools for managing database schema and populating data.
- **Environment Management**: `EnvHelper` for managing environment variables and paths.
- **Command-Line Interface (CLI)**: For handling migrations, seeding, and other tasks.
- **Role-Based Authorization**: Built-in support for managing user roles and permissions.
- **Testability**: Preconfigured testing utilities for unit and integration tests.
- **Framework Extensibility**: Replaceable components like the router, logger, and more.

### Developer Experience
- **Separation of Concerns**: Clean architecture with clear boundaries between features.
- **Dependency Injection**: Support for GetIt-style DI to promote decoupled design.
- **Custom Adapters**: A marketplace-ready adapter system for shared functionality.
- **Readable and Maintainable Code**: Adheres to best practices for long-term reliability.

## Pending Features

- **More Adapters**: Build and publish additional adapters for various integrations.
- **GraphQL Support**: Add built-in support for GraphQL APIs.
- **Advanced Caching**: Extend caching mechanisms with Redis and Memcached.
- **More CLI Commands**: Expand the CLI with additional developer utilities.
- **Improved Error Handling**: Add more robust debugging and logging tools.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/acidlake/forge.git
   cd forge
   ```
2. Run the installer:
   ```bash
   php forge install
   ```
3. Configure environment variables in `.env`.

## Usage

### Running the Server
```bash
php forge serve
```

### Running Migrations
```bash
php forge migrate
```

### Running Seeders
```bash
php forge seed
```

### Rolling Back Migrations
```bash
php forge migrate:rollback
```

### Customizing the Framework
- Replace adapters in the `toolbox/` directory.
- Override base functionality by placing custom files in the `app/` folder.

## Contributing

We welcome contributions from the community! Please follow the guidelines below:
1. Fork the repository.
2. Create a feature branch.
3. Submit a pull request with a detailed description of your changes.

## License

This framework is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Support

For questions or support, open an issue on [GitHub](https://github.com/acidlake/forge/issues).
