INSERT INTO `u_funktionen` (`bezeichnung`)
SELECT
  n.bezeichnung
FROM
  (
    SELECT 'mobile_upload_image' AS bezeichnung
    UNION
    SELECT 'mobile_download_image' AS bezeichnung
    UNION
    SELECT 'mobile_delete_images' AS bezeichnung
  ) AS n LEFT JOIN
  u_funktionen v ON n.bezeichnung = v.bezeichnung
WHERE
  v.bezeichnung IS NULL
      