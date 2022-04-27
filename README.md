## 自动签到
使用 GitHub Actions 自动签到。

### 支持签到平台
- 什么值得买: https://smzdm.com

### 1. 实现功能
+ 通过**多平台**发送签到通知（推送）。参考项目 [Pusher](https://github.com/jetsung/pusher)。
+ 由 `github actions` 每日 8 点、20点定时运行（不太准时）
  > `0 0,12 * * *`    
  > （亲测过几次，这个时间感觉不太准，难道实例每次执行的时区不同？实际时间是 8:40 和 20:15 分左右）      
  > 因 GitHub Actions 为美西时间，+13 小时为中国时间（即设置的时候需要 -13 小时）。

### 2. 使用方法
1. **[Fork](fork)** 此项目，欢迎点 `star`；
2. 设置签到平台的 `cookie` 信息：    
    2.1. 在 **[Secret](settings/secrets/actions)** 新增 `COOKIE_SMZDM`，从[什么值得买官网](https://www.smzdm.com/) 提取的 cookie 信息。   
3. `Fork` 后必须修改一下文件，才能执行定时任务。可修改 `README.md`。

> **[相关变量](.github/workflows/check-in.yml)：**   
>> COOKIE_SMZDM   
>> PUSHDEER_TOKEN   
>> DINGTALK_SECRET   
>> DINGTALK_TOKEN   

### 3. 通知推送（可选）
1. 目前只支持 PushDeer 和 Dingtalk 推送，其它平台可自行参考 [notify.php](notify.php) 文件自行添加。
> 需要在 `.github/workflows/check-in.yml` 添加相应的 `Pusher Token` 环境变量。

### 4. 其它
#### 4.1 cookie 获取方法
> 以下为例子   
1、首先使用chrome浏览器，访问[什么值得买](https://www.smzdm.com/)官网， 登录账号。   
2、Windows / Linux 系统可按 `F12` 快捷键打开开发者工具；Mac 快捷键 `option + command + i`；Linux 还有另一个快捷键 `Ctrl + Shift + i`。笔记本电脑可能需要再加一个 `fn` 键。   
3、选择开发者工具 `Network`，刷新页面 ,选择第一个`www.smzdm.com`, 找到 `Requests Headers` 里的 `Cookie`。

#### 4.2 更改执行时间
在 `.github/check-in.yml`中
```yml
- cron: '0 0,12 * * *'
```

> 语法与 Linux 操作系统的 crontab 计划任务相同，具体可自行搜索 “[`linux crontab`](https://www.man7.org/linux/man-pages/man5/crontab.5.html)”。

**注意：**   
> 1、经测，「什么值得买」平台的 `cookie` 有效期大约半年（180 天）；   
  2、项目长久没有变动，**GitHub Actions** 会休眠，不再触发执行定时任务。此时可进入 `Actions` 找到任意一条成功执行的任务，点击 `Re-run all jobs` 即可。
  