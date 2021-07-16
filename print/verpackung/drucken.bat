REM ### drucken.bat ###
@ECHO OFF
:printpdf

for /r . %%R in (*.pdf) do (


@ping 127.0.0.1 -n 6
pdfp -p \\LZ611401\TLP2844-SN5 "%%R"

@ping 127.0.0.1 -n 8
del "%%R"

@ping 127.0.0.1 -n 10
)
goto printpdf
REM ### drucken.bat ###