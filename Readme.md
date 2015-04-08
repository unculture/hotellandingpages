# Hotel Landing Pages
A command line application to generate landing pages and automatically upload them to S3 for display.

It was created for a client's one-off project, but clear that the process was to be run multiple times over a period of weeks. I took the opportunity to save time by automating the steps of the process.

Example command:
```
time php hl.php --file ./data/dataset1/data.csv  --name test1 --template templates/hl.html --s3bucket bucket-name --awscreds config/aws.json --upload
```


