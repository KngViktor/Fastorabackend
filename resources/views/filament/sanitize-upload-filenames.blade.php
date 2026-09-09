{{--
    The host's ModSecurity refuses any multipart upload whose filename=
    header contains a straight quote, reading it as an SQL injection attempt.
    It answers 403 at the web server, so Laravel never runs, nothing reaches
    the log, and FilePond can only report a bare "Error during upload".

    Measured against production, same endpoint and same file, varying only
    the filename: "plain.jpg" reaches Laravel (419, the expected rejection
    for a request with no CSRF token) while "Don't.jpg" and 'say "hi".jpg'
    both return 403. Parentheses, spaces, ampersands, semicolons, angle
    brackets and backticks all pass, so quotes are the whole of it.

    This is not a hypothetical: covers are named after the post they belong
    to, and post titles routinely contain an apostrophe. An editor hits it on
    an ordinary day with no way to tell what went wrong.

    The quotes only cause trouble in transit, so they are stripped from the
    transmitted filename and nothing else. FileUpload renames the file to a
    generated id on save regardless, so the stored name is unaffected either
    way, and the alt text is what the site actually displays.

    FormData.append is the hook because it is the single point every upload
    passes through -- Livewire sends files with
    formData.append("files[]", file, file.name) -- which keeps this working
    without reaching into Filament's or FilePond's bundled internals, and
    keeps it from breaking when either is upgraded.
--}}
<script>
    (function () {
        var append = FormData.prototype.append

        // Straight quotes are what the firewall rule matches. The curly forms
        // are included because Word, Canva and macOS produce them silently and
        // an editor cannot tell the two apart by looking at the filename.
        var quotes = /['"‘’“”]/g

        FormData.prototype.append = function (name, value, filename) {
            if (value instanceof File) {
                var sent = filename === undefined ? value.name : filename
                var safe = sent.replace(quotes, '')

                if (safe !== sent) {
                    return append.call(this, name, value, safe)
                }
            }

            return append.apply(this, arguments)
        }
    })()
</script>
