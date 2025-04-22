FireVision


A web-based app that can monitor and detect fire through RTSP to WebRTC. It can also be used to monitor remotely. Can be a great use for CCTVs with RTSP support (H.264 codec).

To use, one must have an FFMPEG installed and enter  
```ffmpeg -rtbufsize 100M -i "(RTSP Link)" -vcodec libx264 -an -pix_fmt yuv420p -preset ultrafast -tune zerolatency -bf 0 -b:v 1M -g 30 -f rtsp (Target link)```


For testing, one may use this instead,

```ffmpeg -f dshow -rtbufsize 100M -video_size 640x480 -framerate 30 -i video="(Camera Device)" -vcodec libx264 -an -pix_fmt yuv420p -preset ultrafast -tune zerolatency -bf 0 -b:v 1M -g 30 -f rtsp (Target Link)``` 


To know what camera device to enter, type this:

```ffmpeg -list_devices true -f dshow -i dummy```
