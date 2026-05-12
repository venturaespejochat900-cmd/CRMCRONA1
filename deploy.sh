#!/usr/bin/env bash
set -euo pipefail

# Required env vars injected by GitHub Actions.
: "${DEPLOY_PATH:?DEPLOY_PATH is required}"
: "${DEPLOY_BRANCH:=main}"

cd "$DEPLOY_PATH"

echo "==> Updating code from branch: $DEPLOY_BRANCH"
git fetch origin "$DEPLOY_BRANCH"
git checkout "$DEPLOY_BRANCH"
git reset --hard "origin/$DEPLOY_BRANCH"

echo "==> Deploy completed"