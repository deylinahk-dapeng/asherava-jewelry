# SSH 部署 Asherava Jaxxon 主题

用 SSH + rsync 把本地 `asherava-jaxxon` 同步到服务器，比每次在 WordPress 后台上传 zip 更快，也方便 Cursor Agent 一条命令更新。

## 你需要准备的信息

在 **SpinUpWP**（或主机面板）里找到：

| 项 | 示例 |
|----|------|
| 服务器 IP | `123.45.67.89` |
| SSH 用户名 | 多为 `forge` 或 SpinUpWP 里显示的 deploy 用户 |
| 站点根目录 | `/sites/asherava.com/files` |
| 主题目录 | `/sites/asherava.com/files/wp-content/themes/asherava-jaxxon` |

SpinUpWP 文档：[Server Paths](https://spinupwp.com/doc/server-paths-locations/) — 网站文件在 `/sites/{域名}/files`。

---

## 第一步：在 Mac 上生成 SSH 密钥（只需一次）

终端执行：

```bash
ssh-keygen -t ed25519 -C "asherava-deploy" -f ~/.ssh/asherava_deploy
```

一路回车即可（ passphrase 可选，更安全但 Agent 自动部署时要你本机已解锁 ssh-agent）。

查看公钥（待会要粘贴到服务器）：

```bash
cat ~/.ssh/asherava_deploy.pub
```

---

## 第二步：把公钥加到服务器

**SpinUpWP：**

1. 登录 [SpinUpWP](https://spinupwp.com) → **Account** → **SSH Keys**
2. 粘贴 `asherava_deploy.pub` 全文 → Save
3. 打开你的 **Server** → 确认该服务器已关联这把密钥

或在服务器上（你有 root 时）：

```bash
# 以你的 SSH 用户登录后
mkdir -p ~/.ssh && chmod 700 ~/.ssh
echo '粘贴公钥一整行' >> ~/.ssh/authorized_keys
chmod 600 ~/.ssh/authorized_keys
```

---

## 第三步：测试能否登录

把 `USER` 和 `IP` 换成 SpinUpWP / DO 里显示的：

```bash
ssh -i ~/.ssh/asherava_deploy USER@IP
```

成功的话会看到 shell 提示符。输入 `exit` 退出。

确认主题目录存在：

```bash
ls /sites/asherava.com/files/wp-content/themes/asherava-jaxxon/style.css
```

---

## 第四步：配置本机 SSH 别名（推荐）

编辑 `~/.ssh/config`（没有就新建）：

```
Host asherava
  HostName 你的服务器IP
  User 你的SSH用户名
  IdentityFile ~/.ssh/asherava_deploy
  IdentitiesOnly yes
```

再测：

```bash
ssh asherava "echo OK"
```

---

## 第五步：项目里启用 deploy.env

```bash
cd ~/Projects/asherava-jaxxon
cp deploy.env.example deploy.env
```

编辑 `deploy.env`：

```
SSH_HOST=asherava
REMOTE_THEME_DIR=/sites/asherava.com/files/wp-content/themes/asherava-jaxxon
```

`deploy.env` 已在 `.gitignore`，不要提交密码或密钥。

---

## 第六步：部署

```bash
chmod +x scripts/deploy.sh
./scripts/deploy.sh
```

脚本用 **rsync** 同步整个主题目录（删除服务器上已不存在的旧文件）。改完 CSS 后若线上仍旧样式，在 SpinUpWP 站点里 **Purge All Caches**。

---

## 给 Cursor Agent 用

Agent 在你这台 Mac 上跑命令，**不需要**把 SSH 私钥发给 Cursor：

1. 你按上面配好 `~/.ssh/config` + `deploy.env`
2. 对我说：「用 SSH 部署主题」→ 我会执行 `./scripts/deploy.sh`
3. 若命令要网络，批准 **full_network** 即可

**不要**在聊天里发：SSH 密码、私钥、`deploy.env` 全文。只需告诉我「已配好，Host 叫 asherava」。

---

## 常见问题

**Permission denied (publickey)**  
公钥未加到 SpinUpWP/服务器，或 `User`/`IdentityFile` 写错。

**No such file ... asherava-jaxxon**  
主题还没在服务器安装过一次：先在 WP 后台上传 zip 装一遍，或手动 `mkdir` 父目录后再 rsync。

**改了代码线上没变**  
SpinUpWP / CDN 缓存 → 后台 Purge cache；或 `style.css` 里把 `Version` 和 `ASHERAVA_JAXXON_VERSION` 加 0.0.1。

**只有 SFTP 没有 SSH**  
部分共享主机只开 SFTP（同样 22 端口），把 `rsync` 换成 FileZilla 同步同一远程路径即可；Agent 仍可用 `curl` 走 WordPress 上传。

---

## 安全建议

- 单独 SSH 密钥只用于部署，不要和个人 GitHub 密钥混用
- 服务器上该用户只需写 `wp-content/themes/asherava-jaxxon`，不要给 root
- 定期轮换；离职/换电脑时从 SpinUpWP 删除旧公钥
