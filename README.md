# steem-tools
面向国人的Steem工具集

# 部署

## 克隆代码

```
git clone https://github.com/ety001/steem-tools.git
```

## 安装依赖

```
composer install
```

## 修改配置文件

```
cp .env.example .env
```

除了需要修改数据库配置外，还需要修改下面的这些项

```
WECHAT_OFFICIAL_ACCOUNT_APPID=   # 微信服务号的App ID
WECHAT_OFFICIAL_ACCOUNT_SECRET=  # 微信服务号的Secret
WECHAT_OFFICIAL_ACCOUNT_TOKEN=   # 微信服务号的Token
WECHAT_OFFICIAL_ACCOUNT_AES_KEY= # 如果需要加密通信，则输入微信服务号的AES KEY
WECHAT_TMPL_REPLY_ID=            # 消息模板ID（回复）
WECHAT_TMPL_TRANSFER_ID=         # 消息模板ID（转账）
WECHAT_TMPL_CHANGE_ID=           # 消息模板ID（账户资金变动）

# 下面是 steemconnect 的配置信息
STEEM_SC2_ID=                   
STEEM_SC2_SECRET=
STEEM_SC2_RETURN_URL=

# 下面是 steem api
STEEM_API=https://steemd.privex.io
```

## 导入数据库

```
php artisan migrate
```

## 配置nginx
请自行配置，假如配置的域名是 `http://steem.test`

## 配置watcher
`watcher`的作用是获取最新块并解析数据转发给`php`端。
```
docker run -itd --name steem_watcher \
    -e "API_URL=http://steem.test/block" \
    -e "STEEMD=https://steemit.com" \
    --restart always \
    ety001/steem-mention:latest
```
> 其中`API_URL`是`php`端接收数据的接口，`STEEMD`是`steem api url`。
