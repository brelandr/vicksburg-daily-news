#!/usr/bin/env bash
#
# Build a distributable theme zip with a UNIQUE filename each run.
#
# - Zip name: <theme-folder>-<style.css-Version>-<timestamp>.zip
#   Only the archive filename carries the build suffix; Theme Version inside style.css is unchanged.
# - Archive root: ONE folder matching the theme directory name (slug), e.g. vicksburg-daily-news/
#   so unzip always expands to ./vicksburg-daily-news/…
#
# Run from THIS directory (next to style.css and this script).
#
# Prerequisites: bash, rsync, zip.
#
# Output: sibling dist/ beside this theme folder, e.g. ../dist/<basename>-Version-build-<timestamp>.zip
#

set -e

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
WORKSPACE_DIR="$( cd "${SCRIPT_DIR}/.." && pwd )"
DIST_DIR="${WORKSPACE_DIR}/dist"
STYLE_FILE="${SCRIPT_DIR}/style.css"

THEME_SLUG="$( basename "${SCRIPT_DIR}" )"

if [[ ! -f "${STYLE_FILE}" ]]; then
	echo "Missing ${STYLE_FILE}: place this script in the WordPress theme root next to style.css." >&2
	exit 1
fi

read_style_version() {
	# First "Version:" header line in style.css (WordPress convention).
	local line=""
	line="$( grep -im1 '^[[:space:]]*Version:[[:space:]]*' "${STYLE_FILE}" || true )"
	line="${line#*:}"
	line="$( echo "${line}" | sed 's/^[[:space:]]*//' | tr -d '\r' )"
	line="$( echo "${line}" | awk '{ print $1 }' )"
	line="${line//\//-}"
	line="${line// /_}"
	if [[ -z "${line}" ]] || [[ "${line}" == "Version:" ]]; then
		echo 'unknown'
	else
		echo "${line}"
	fi
}

STYLE_VER="$( read_style_version )"
BUILD_TS="$( date +'%Y%m%d-%H%M%S' )"
ZIP_NAME="${THEME_SLUG}-${STYLE_VER}-build-${BUILD_TS}.zip"

RSYNC_EXCLUDES=(
	--exclude='.*'
	--exclude='.build-theme-zip/'
	--exclude='.cursor/'
	--exclude='.git/'
	--exclude='.github/'
	--exclude='.sass-cache/'
	--exclude='sass-cache/'
	--exclude='node_modules/'
	--exclude='vendor/'
	--exclude='*.zip'
	--exclude='*.sh'
	--exclude='*.log'
	--exclude='error_log'
	--exclude='.DS_Store'
)

BUILD_DIR="${WORKSPACE_DIR}/.build-theme-zip-${THEME_SLUG}-$$"
STAGING_THEME="${BUILD_DIR}/${THEME_SLUG}"

mkdir -p "${DIST_DIR}"
ZIP_OUT="${DIST_DIR}/${ZIP_NAME}"

rm -rf "${BUILD_DIR}"
mkdir -p "${STAGING_THEME}"

rsync -a "${RSYNC_EXCLUDES[@]}" "${SCRIPT_DIR}/" "${STAGING_THEME}/"

cd "${BUILD_DIR}"
zip -r "${ZIP_OUT}" "${THEME_SLUG}" -x "*.DS_Store" -x "*.log" -x "*/error_log"

cd "${WORKSPACE_DIR}" > /dev/null
rm -rf "${BUILD_DIR}"

echo "✅ Created ${ZIP_NAME} in ${DIST_DIR}"
