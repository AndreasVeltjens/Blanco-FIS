REM ### drucken.bat ###
@ECHO OFF
:printpdf

for /r . %%R in (*.pdf) do (

@ping 127.0.0.1 -n 1

pdfp -p \\LZ611404\TLP2844-SN8 "%%R"

@ping 127.0.0.1 -n 10
del "%%R"

@ping 127.0.0.1 -n 20
)
goto printpdf
REM ### drucken.bat ###