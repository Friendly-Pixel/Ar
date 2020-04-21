#!/usr/bin/env node

const { execSync } = require("child_process");
const path = require("path");
const util = require("util");
const fs = require("fs");
const readline = require("readline").createInterface({
  input: process.stdin,
  output: process.stdout
});

/**
 * Prepare a major, minor or patch release.
 */
const types = ["major", "minor", "patch"];
const type = process.argv[2];
const typeIndex = types.indexOf(type);
if (process.argv.length != 3 || !types.includes(type)) {
  console.error(
    `Usage: ./${path.basename(process.argv[1])} [major|minor|patch]`
  );
  process.exit(1);
}

process.argv;

// Get latest git version tag
const tags = execSync("git tag -l")
  .toString()
  .trim()
  .split("\n")
  .map(tag => tag
    .replace("v", "")
    .trim()
    .split(".")
    .map(p => parseInt(p))
  )
  .sort((a, b) => {
    for (let i = 0; i < 3; i++) {
      if (a[i] != b[i]) return b[i] - a[i];
    }
    return 0;
  })
;
if (!tags.length) {
  throw "Could not load git tag names";
}
const tagParts = tags[0];
const tag = `v${tagParts.join('.')}`;
console.log(`Last version: ${tag}`);

// Create new version
const newVersionParts = tagParts.slice().map((value, i) => {
  if (i == typeIndex) value++;
  if (i > typeIndex) value = 0;
  return value;
});
const newTag = `v${newVersionParts.join(".")}`;
console.log(`New version tag: ${newTag}`);

execSync(`git add -A`);

confirm(`This will:
* git tag
* git push

Please  check git staging area

Type y to confirm:`).then(() => {
  execSync(`git tag ${newTag}`);
  execSync(`git push`);
  execSync(`git push --tags`);
});

function confirm(question) {
  return new Promise(resolve => {
    readline.question(question, answer => {
      readline.close();
      resolve(answer);
    });
  });
}

function replaceInFile(filename, searchValue, replaceValue) {
  return fs.writeFileSync(
    filename,
    fs
      .readFileSync(filename)
      .toString()
      .replace(searchValue, replaceValue)
  );
}
