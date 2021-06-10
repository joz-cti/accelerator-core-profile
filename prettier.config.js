module.exports = {
  printWidth: 80,
  tabWidth: 2,
  semi: true,
  singleQuote: true,
  trailingComma: 'all',
  overrides: [
    {
      files: '{*.scss,*.md}',
      options: {
        singleQuote: false,
      },
    },
    {
      files: '{*.yaml,*.yml}',
      options: {
        // Workaround for https://github.com/prettier/prettier/issues/5599
        printWidth: 99999,
      },
    },
    {
      files: '*.md',
      options: {
        printWidth: 80,
        proseWrap: 'always',
      },
    },
  ],
};
