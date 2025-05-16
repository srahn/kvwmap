#!/bin/bash

# Usage: ./download_parallel.sh <url_file> <parallel_limit>
# <url_file>: A file containing a list of URLs to download, one URL per line.
# <parallel_limit>: Number of parallel downloads (default: 4).

# Check if at least one argument (the URL file) is provided
if [ "$#" -lt 1 ]; then
  echo "Usage: $0 <url_file> <dest_path> [parallel_limit]"
  exit 1
fi

# Assign arguments to variables
URL_FILE="$1"
DOWNLOAD_DIR="$2"
PARALLEL_LIMIT=${3:-4} # Default to 4 parallel downloads if not specified
ONLY_MISSING=${4:-0} # Default to 0

# Check if the URL file exists
if [ ! -f "$URL_FILE" ]; then
  echo "Error: File '$URL_FILE' not found."
  exit 1
fi

# Create a subdirectory for downloads
mkdir -p "$DOWNLOAD_DIR"
# Download files in parallel using xargs and wget
cat "$URL_FILE" | while read url; do
  file="${DOWNLOAD_DIR}/$(basename $url)"
  if [ "$ONLY_MISSING" == 0 ] || [ ! -f $file ] ; then
    echo "wget \"${url}\" -q --show-progress -P ${DOWNLOAD_DIR}"
  fi
done | xargs -I {} -P $PARALLEL_LIMIT bash -c '{}'
wait
# cat "$URL_FILE" | xargs -n 1 -P "$PARALLEL_LIMIT" wget -q --show-progress -P "$DOWNLOAD_DIR"

if [ $? -eq 0 ]; then
  echo "All downloads completed successfully. Files saved in '$DOWNLOAD_DIR'."
else
  echo "Some downloads failed. Check the output above for details."
fi
