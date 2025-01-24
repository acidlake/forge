# Contributing to Forge Framework

We appreciate your interest in contributing to the Forge Framework! Your contributions help make this framework robust, flexible, and user-friendly for the entire community. Whether you’re fixing bugs, adding new features, or improving documentation, every contribution matters.

This document outlines our guidelines for contributing.

## Getting Started

1. **Fork the Repository**:
   - Visit the [Forge Framework GitHub repository](https://github.com/acidlake/forge) and click the **Fork** button.
   - Clone your fork to your local machine:
     ```bash
     git clone https://github.com/acidlake/forge.git
     cd forge
     ```

2. **Create a Branch**:
   - Use a descriptive name for your branch that explains the changes you’re making.
     ```bash
     git checkout -b feature/your-feature-name
     ```

3. **Install Dependencies**:
   - Ensure you have all the required dependencies installed:
     ```bash
     php forge install
     ```

4. **Run Tests**:
   - Before making changes, ensure the current tests pass:
     ```bash
     phpunit
     ```

## How to Contribute

### Reporting Bugs
- Search the [issues page](https://github.com/acidlake/forge/issues) to see if the bug has already been reported.
- If not, create a new issue and include:
  - A clear and descriptive title.
  - Steps to reproduce the problem.
  - Expected and actual behavior.
  - Any relevant logs, screenshots, or configuration details.

### Suggesting Features
- Check the [issues page](https://github.com/acidlake/forge/issues) to ensure your feature hasn’t already been suggested.
- Create a new issue with the following details:
  - A descriptive title for your feature request.
  - Why this feature would be beneficial.
  - Any possible implementation ideas.

### Code Contributions

1. **Make Your Changes**:
   - Follow the framework’s coding standards.
   - Write clean, maintainable, and well-documented code.

2. **Test Your Changes**:
   - Add tests for any new functionality or bug fixes.
   - Run all tests to ensure your changes don’t introduce regressions:
     ```bash
     phpunit
     ```

3. **Commit Your Changes**:
   - Write clear and descriptive commit messages:
     ```bash
     git add .
     git commit -m "Add feature: your-feature-name"
     ```

4. **Push to Your Fork**:
   ```bash
   git push origin feature/your-feature-name
   ```

5. **Create a Pull Request**:
   - Navigate to your fork on GitHub.
   - Click the **Pull Request** button.
   - Provide a clear description of your changes, referencing any related issues.

## Code Style and Best Practices

- Follow the PSR-12 coding standard for PHP.
- Use meaningful variable and function names.
- Write tests for new features or bug fixes.
- Avoid introducing breaking changes without prior discussion.
- Keep your changes focused and avoid combining multiple features or fixes in a single pull request.

## Reviewing Pull Requests

- All pull requests will be reviewed by a maintainer.
- Feedback will be provided, and changes may be requested before merging.
- Ensure your branch is up to date with the latest `main` branch to avoid merge conflicts.

## Community Guidelines

- Be respectful and considerate in all communications.
- Provide constructive feedback when reviewing contributions.
- Foster a welcoming environment for contributors of all skill levels.

## Need Help?

If you have questions or need assistance:
- Open a discussion in the [GitHub Discussions](https://github.com/acidlake/forge/discussions) section.
- Tag a maintainer or community member in your issue or pull request.

Thank you for contributing to Forge Framework! Together, we’re building a powerful and flexible tool for the PHP community.
