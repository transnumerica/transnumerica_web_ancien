@echo off

set path=%~d0%~p0

:start

"%path%pngquant.exe" --force --skip-if-larger --verbose --ext=.png --speed=1 --quality=0-60 %1

shift
if NOT x%1==x goto start
