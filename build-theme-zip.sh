#!/usr/bin/env bash
#
# Build a WordPress-ready theme ZIP from this directory:
# - Optional: bump patch in style.css (Version: line)
# - Output:  ../dist/<folder-name>-<version>.zip
# - Contents: ONE top-level folder = theme folder basename (NO version suffix), e.g.
#               vicksburg-daily-news/style.css
#
# Usage:
#   ./build-theme-zip.sh           # bump patch + create zip
#   ./build-theme-zip.sh --no-bump # use existing Version line for naming only
#   ./build-theme-zip.sh -h|--help

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SLUG="$(basename "$SCRIPT_DIR")"
STYLE="${SCRIPT_DIR}/style.css"
PARENT_DIR="$(dirname "$SCRIPT_DIR")"
DIST_DIR="${PARENT_DIR}/dist"

BUMP=1
for arg in "$@"; do
	case "$arg" in
		-h|--help)
			sed -n '1,25p' "$0" | tail -n +2
			exit 0
			;;
		--no-bump|--no-bump-version)
			BUMP=0
			;;
		*)
			echo "Unknown option: $arg (use --help)" >&2
			exit 1
			;;
	esac
done

if [[ ! -f "$STYLE" ]]; then
	echo "Missing style.css — run from theme root (${STYLE})." >&2
	exit 1
fi

read_style_version() {
	grep -m1 -E '^[[:space:]]*Version[[:space:]]*:' "$STYLE" \
		| sed -E 's/^[[:space:]]*Version[[:space:]]*:[[:space:]]*//; s/[[:space:]]+$//'
}

CURRENT_VER="$(read_style_version)"
if [[ -z "${CURRENT_VER}" ]]; then
	echo "Could not read Version: from style.css" >&2
	exit 1
fi

NEW_VER="$CURRENT_VER"
if [[ "$BUMP" -eq 1 ]]; then
	IFS=. read -r ma mi pa <<<"${CURRENT_VER}.0.0"
	# Fallback if version has only 2 parts
	ma="${ma:-0}"
	mi="${mi:-0}"
	pa="${pa:-0}"
	if ! [[ "$ma" =~ ^[0-9]+$ && "$mi" =~ ^[0-9]+$ && "$pa" =~ ^[0-9]+$ ]]; then
		echo "Version '${CURRENT_VER}' is not x.y.z numeric — bump manually or use --no-bump" >&2
		exit 1
	fi
	NEW_VER="${ma}.${mi}.$((pa + 1))"

	# Cross-platform in-place edit (macOS vs GNU sed)
	if sed --version >/dev/null 2>&1; then
		sed -i "s/^Version:[[:space:]].*/Version: ${NEW_VER}/" "$STYLE"
	else
		sed -i '' "s/^Version:[[:space:]].*/Version: ${NEW_VER}/" "$STYLE"
	fi
	echo "Bumped style.css Version: ${CURRENT_VER} → ${NEW_VER}"
else
	echo "Using existing style.css Version: ${NEW_VER} (--no-bump)"
fi

mkdir -p "$DIST_DIR"
ZIP_PATH="${DIST_DIR}/${SLUG}-${NEW_VER}.zip"
STAGE_ROOT="$(mktemp -d "${TMPDIR:-/tmp}/theme-zip-${SLUG}.XXXXXX")"
cleanup() { rm -rf "$STAGE_ROOT"; }
trap cleanup EXIT

STAGE_THEME="${STAGE_ROOT}/${SLUG}"
mkdir -p "$STAGE_THEME"

RSYNC=(
	rsync -a
	--delete
	# VCS / editor / OS noise
	--exclude='.git'
	--exclude='.svn'
	--exclude='.hg'
	--exclude='.cursor'
	--exclude='.vscode'
	--exclude='*.swp'
	--exclude='.DS_Store'
	--exclude='Thumbs.db'
	--exclude='node_modules'
	--exclude='npm-debug.log*'
	# Build output near theme (don’t recurse into sibling zips/dists)
	--exclude='*.zip'
	--exclude='/dist/'
	# This script stays in repo root; harmless to ship
)

"${RSYNC[@]}" "${SCRIPT_DIR}/" "${STAGE_THEME}/"

(
	cd "$STAGE_ROOT"
	rm -f "$ZIP_PATH"
	zip -r -q "$ZIP_PATH" "$SLUG"
)

echo ""
echo "Created: ${ZIP_PATH}"
echo "Archive root folder: ${SLUG}/"
