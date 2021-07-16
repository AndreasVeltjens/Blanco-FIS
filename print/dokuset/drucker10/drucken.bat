REM ### drucken.bat ###
@ECHO OFF
:printpdf

for /r . %%R in (*.pdf) do (
@ping 127.0.0.1 -n 8



pdfp -p \\LZPCPR01\DELLLEDLASER "%%R"

@ping 127.0.0.1 -n 3
del "%%R"

@ping 127.0.0.1 -n 20
)
goto printpdf
REM ### drucken.bat ###