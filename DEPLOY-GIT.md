# Git Push-to-Deploy（SpinUpWP + GitHub）

推送 `main` 分支 → GitHub Webhook → SpinUpWP 拉代码 → **Deploy Script** 把主题同步到  
`wp-content/themes/asherava-jaxxon/`。

> **重要：** 本仓库**只有子主题**，不是整站 WordPress。必须在 SpinUpWP 里配置下面的 Deploy Script，否则不要把整站改成「从 Git 克隆新建站点」。

---

## 一、GitHub 仓库（你操作一次）

1. 打开 https://github.com/new  
2. **Repository name：** `asherava-jaxxon`  
3. **Private** 推荐  
4. **不要**勾选 “Add a README”（本地已有代码）  
5. 创建仓库后，记下 SSH 地址，例如：  
   `git@github.com:你的用户名/asherava-jaxxon.git`

### 本机首次推送

```bash
cd ~/Projects/asherava-jaxxon
git init
git add .
git commit -m "Initial commit: Asherava Jaxxon theme 1.1.2"
git branch -M main
git remote add origin git@github.com:你的用户名/asherava-jaxxon.git
git push -u origin main
```

若尚未配置 GitHub SSH，先在 GitHub → **Settings → SSH Keys** 添加本机 `~/.ssh/id_ed25519.pub`（或 `asherava_deploy.pub`）。

---

## 二、SpinUpWP 连接 GitHub

### 1. SpinUpWP ↔ GitHub SSH

1. SpinUpWP → **Account** → 配置与 **GitHub** 的 SSH（按面板提示操作）  
2. 或：站点 **Git** 页 → 复制 **Deploy Key** → GitHub 仓库 → **Settings → Deploy keys → Add**  
   - Title: `spinupwp-asherava`  
   - 只读即可（Read-only）  
   - 粘贴 SpinUpWP 提供的公钥  

### 2. 站点 asherava.com → Git 标签

在**现有站点**上启用（不要新建「从 Git 克隆」站点，以免覆盖整站）：

| 字段 | 填写 |
|------|------|
| Repository URL | `git@github.com:你的用户名/asherava-jaxxon.git` |
| Branch | `main` |
| **Deploy Script** | 见下方 |

**Deploy Script**（复制 `scripts/spinupwp-deploy.sh` 全文粘贴到文本框）：

```bash
#!/usr/bin/env bash
set -euo pipefail
THEME_SLUG="asherava-jaxxon"
DEST="wp-content/themes/${THEME_SLUG}"
mkdir -p "$DEST"
sync_item() { local item="$1"; [[ -e "$item" ]] || return 0; rsync -a --delete "$item" "${DEST}/"; }
sync_item style.css
sync_item functions.php
sync_item front-page.php
sync_item assets
sync_item inc
sync_item template-parts
for item in style.css functions.php front-page.php assets inc template-parts; do
  [[ -e "./${item}" && "${item}" != "wp-content" ]] && rm -rf "./${item}"
done
command -v wp >/dev/null && wp cache flush --allow-root 2>/dev/null || true
echo "Deployed theme to ${DEST}"
```

> 若 Deploy Script 的工作目录是站点根而非 `files`，在脚本**第一行**加：`cd files`

3. 保存后先点 **Deploy Now**（手动部署一次），确认主题正常。  
4. 打开 **Push to Deploy** → 复制 **Deployment URL**。

---

## 三、GitHub Webhook

仓库 → **Settings → Webhooks → Add webhook**：

| 项 | 值 |
|----|-----|
| Payload URL | SpinUpWP 的 Deployment URL |
| Content type | `application/json` |
| Secret | 留空 |
| Events | Just the **push** event |

保存后，任意 `git push` 到 `main` 都会触发部署。

---

## 四、日常流程（你或 Cursor）

```bash
cd ~/Projects/asherava-jaxxon
# 改代码…
git add .
git commit -m "Update header styles"
git push
```

约 10–30 秒后线上更新；可在 SpinUpWP 部署日志里查看是否成功。

**Cursor Agent：** 改完代码后说「push 部署主题」，我会 `git commit` + `git push`（需你已配好 GitHub SSH）。

---

## 五、与 SSH rsync 的关系

| 方式 | 何时用 |
|------|--------|
| **Git push（本方案）** | 日常自动部署 |
| `./scripts/deploy.sh` | 紧急热修、Git 未通时备用 |

---

## 六、首次启用请注意

- 先在 SpinUpWP 做**站点备份**（或 DO 快照）。  
- 第一次 **Deploy Now** 后检查：首页、商店、后台是否正常。  
- 若样式未变：**Purge All Caches**。

---

## 七、排错

| 现象 | 处理 |
|------|------|
| Deploy 失败 / permission denied | 检查 GitHub Deploy Key 是否加到**该仓库** |
| 主题没更新 | Deploy Script 是否粘贴完整；是否需 `cd files` |
| 整站白屏 | 不要用「仅含主题」的仓库做**新建** Git 站点；用 Deploy Script 同步到 themes |
| Webhook 不触发 | GitHub Webhook → Recent Deliveries 看是否 200 |
