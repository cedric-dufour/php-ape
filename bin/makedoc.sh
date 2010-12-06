#!/bin/bash
#23456789 123456789 123456789 123456789 123456789 123456789 123456789 123456789

# Arguments
[ $# -lt 2 ] && cat <<EOF && exit
USAGE: ${0##*/} <source-directory> <output-directory>

SYNOPSIS:
 Create the HTML documentation for the PHP source code, using 'phpdoc'
 (see PHP::PEAR 'phpDocumentor')

EOF

# Arguments
DOC_SOURCE="$1"
DOC_OUTPUT="$2"

# Extract full command path
cd "$(dirname $0)" && DOC_CMDPATH="$(pwd)"
cd - > /dev/null

# Extract full source path
cd "${DOC_SOURCE}" && DOC_SOURCE="$(pwd)"
cd - > /dev/null

# Check source directory
[ ! -d "${DOC_SOURCE}/cgi-bin" -a ! -d "${DOC_SOURCE}/htdocs" ] && echo "ERROR: No source found in specified directory (${DOC_SOURCE})" && exit

# Documentation settings
PHPDOC="${DOC_CMDPATH}/phpdoc.php.sh"
PHPDOC_TITLE="PHP Application Programming Environment (PHP-APE)"
PHPDOC_PACKAGE="PHP_APE"
[ -d "${DOC_SOURCE}/cgi-bin" ] && PHPDOC_SOURCE="${PHPDOC_SOURCE:+,}${DOC_SOURCE}/cgi-bin"
[ -d "${DOC_SOURCE}/htdocs" ] && PHPDOC_SOURCE="${PHPDOC_SOURCE:+,}${DOC_SOURCE}/htdocs"
[ -d "${DOC_SOURCE}/src/doc/phpdoc" ] && PHPDOC_SOURCE="${PHPDOC_SOURCE:+,}${DOC_SOURCE}/src/doc/phpdoc"
PHPDOC_IGNORE="*/js/,*/css/"
PHPDOC_OUTPUT="${DOC_OUTPUT}"
PHPDOC_FORMAT=HTML:frames:DOM/earthli
PHPDOC_OPTIONS="-s on" # -s on -pp on | egrep -i '^(error|warning)'

# Check and clean output directory
echo "Documenting in: ${DOC_OUTPUT}"
[ -z "${PHPDOC_OUTPUT}" ] && echo "ERROR: Output directory is not set" && exit
[ ! -e "${PHPDOC_OUTPUT}" ] && mkdir -p "${PHPDOC_OUTPUT}"
[ ! -e "${PHPDOC_OUTPUT}" ] && echo "ERROR: Failed to create output directory (${PHPDOC_OUTPUT})" && exit
rm -rf "${PHPDOC_OUTPUT}/*"

# Create documention
${PHPDOC} -t "${PHPDOC_OUTPUT}" -i ${PHPDOC_IGNORE} -d "${PHPDOC_SOURCE}" -o "${PHPDOC_FORMAT}" -ti "${PHPDOC_TITLE}" -dn "${PHPDOC_PACKAGE}" ${PHPDOC_OPTIONS}

# Copy images
if [ -d "${DOC_SOURCE}/src/doc/phpdoc" ]; then
  images=`find "${DOC_SOURCE}/src/doc/phpdoc" -type f \( -iname "*.png" -o -iname "*.gif" -o -iname "*.jpg" \)`
  echo "${images}" | while read image
  do
	  cp "${image}" "${PHPDOC_OUTPUT}/${image##${DOC_SOURCE}/src/doc/phpdoc/*/tutorials/}"
  done
fi
