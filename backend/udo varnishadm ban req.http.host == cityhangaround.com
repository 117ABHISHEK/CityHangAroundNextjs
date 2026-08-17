[0;1;32m●[0m varnish.service - Varnish Cache, a high-performance HTTP accelerator
     Loaded: loaded (]8;;file://ubuntu-16gb-hel1-1/usr/lib/systemd/system/varnish.service/usr/lib/systemd/system/varnish.service]8;;; [0;1;32menabled[0m; preset: [0;1;32menabled[0m)
     Active: [0;1;32mactive (running)[0m since Sun 2026-05-17 13:04:08 IST; 1 month 19 days ago
   Main PID: 992 (varnishd)
      Tasks: 217
     Memory: 90.8M (peak: 119.3M swap: 3.2M swap peak: 3.7M)
        CPU: 44min 20.073s
     CGroup: /system.slice/varnish.service
             ├─[0;38;5;245m 992 /usr/sbin/varnishd -a :6081 -f /etc/varnish/default.vcl -P /run/varnish/varnishd.pid -p feature=+http2 -p http_resp_hdr_len=40000 -s malloc,512m[0m
             └─[0;38;5;245m1601 /usr/sbin/varnishd -a :6081 -f /etc/varnish/default.vcl -P /run/varnish/varnishd.pid -p feature=+http2 -p http_resp_hdr_len=40000 -s malloc,512m[0m

Notice: journal has been rotated since unit was started, output may be incomplete.
