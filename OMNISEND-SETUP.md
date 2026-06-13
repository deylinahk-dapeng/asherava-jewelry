# Omnisend 安装与 LZJ 首访弹窗配置

线上已安装并启用 **Email Marketing for WooCommerce by Omnisend**（`omnisend-connect`）。  
已创建 WooCommerce 优惠券 **`WELCOME10`**（10% 折扣，每人限用 1 次，90 天有效）。

你只需完成 **Omnisend 账号连接** 和 **弹窗/自动化**（在 Omnisend 后台操作，无法代你登录）。

---

## 一、连接 WordPress ↔ Omnisend（约 5 分钟）

1. 注册：https://app.omnisend.com/ （选 **Free**）
2. WordPress 后台 → **Omnisend**（左侧菜单）→ **Connect store**
3. 按提示授权；确认站点 URL 为 `https://asherava.com`
4. 连接成功后，WooCommerce 客户/订单会同步到 Omnisend

---

## 二、WooCommerce 优惠券（已完成）

| 项目 | 值 |
|------|-----|
| Code | `WELCOME10` |
| 类型 | 10% Percentage |
| 每人限用 | 1 次 |
| 过期 | 约 90 天（可在 Marketing → Coupons 调整） |

---

## 三、LZJ 风格「首访」弹窗逻辑（对齐珠宝 DTC 站）

Luke Zion 类站点常见行为（Shopify + Klaviyo/Omnisend 同类）：

- **不要**一进站就弹（避免秒关）
- **首访新客**延迟后出现居中弹窗
- 提供 **10% off first order**，留邮箱后发码
- 关闭后 **一段时间内不再打扰**
- 已订阅用户 **不再显示**

### Omnisend：创建弹窗

**Forms → Create form → Popup**

#### 文案（英文，可直接粘贴）

| 元素 | 文案 |
|------|------|
| 标题 | **GET 10% OFF** |
| 副标题 | Sign up for exclusive offers on men's chain jewelry. |
| 按钮 | **GET MY 10% OFF** |
| 底部小字 | By subscribing you agree to receive marketing emails. Unsubscribe anytime. |

#### 样式（对齐 Asherava / LZJ）

| 项 | 建议 |
|----|------|
| 背景 | `#1f1f1f` 或 `#000000` |
| 文字 | `#ffffff` |
| 按钮 | 白底黑字，或黑底白字细边框 |
| 字体 | Source Sans Pro / 无衬线 |
| 圆角 | 0–4px（偏硬朗） |

#### Behavior → Display（首访逻辑，重点）

| 设置 | 推荐值 | 说明 |
|------|--------|------|
| **When to show** | **Time on site** | 非立即弹出 |
| 延迟 | **12 秒**（首页）；可 A/B 10–15 秒 | 对标 LZJ / Omnisend 首页建议 10–15s |
| 额外规则（可选） | **Scroll depth 25%**（仅首页） | 用户先看到 Hero 再弹 |
| **Who sees it** | **New visitors** / 未订阅 | 首访逻辑 |
| **Pages** | All pages **或** 仅 Homepage + Shop + Product | 新店可先全站；要更贴 LZJ 可仅前台浏览页 |
| **Devices** | Desktop + Mobile | 与 LZJ 一致 |
| **Frequency** | 关闭后 **7 天内不再显示**；订阅后 **永不显示** | 避免烦人 |
| **Exit intent** | 可选：Shop / Cart 页开启 | 离开前再抓一次 |

> 产品页可单独再建一个表单：延迟 **20–30 秒**（Omnisend 建议），首访逻辑相同。

#### Form 提交后

- **Tag contacts**：`welcome-popup`（或 `newsletter-10`）
- 进入自动化发邮件（见下一节）

---

## 四、欢迎邮件自动化（发 WELCOME10）

**Automation → Create workflow**

1. **Trigger**：Contact subscribed **或** Tag added `welcome-popup`
2. **Email**（立即发送）：

**Subject:** Your 10% off is inside — Asherava

**Body 要点:**

- Thanks for joining.
- Use code **WELCOME10** at checkout for **10% off your first order**.
- Valid 90 days. One use per customer.
- Link: `https://asherava.com/shop/`
- Unsubscribe link（Omnisend 自动插入）

3. **Enable** workflow

---

## 五、验收清单

- [ ] 隐身窗口打开 https://asherava.com ，约 **12 秒**后出现弹窗
- [ ] 关闭后同会话不再出现；7 天内复访不重复（按 Frequency 设置）
- [ ] 提交测试邮箱后收到邮件，内含 **WELCOME10**
- [ ] 结账输入 `WELCOME10` 价格减 10%
- [ ] 手机端弹窗不被顶栏完全挡住（Omnisend 默认居中一般 OK）
- [ ] SpinUpWP **Purge cache** 后复测

---

## 六、与 Coming Soon 的关系

若整站仍为 Coming Soon，访客可能看不到商店；弹窗仍可收集邮箱，但**无法立刻试券**。  
建议：Coming Soon 期间弹窗照常收邮箱；正式开业同一批人再发开业邮件。

---

## 七、弹窗关不掉（常见原因）

| 现象 | 处理 |
|------|------|
| 点灰色背景关不掉 | Omnisend 表单 **Behavior** → 开启 **Close when clicking outside**（点击遮罩关闭） |
| 手机点 X 没反应 | X 在右上角，可能被顶栏挡住；主题 v1.1.9 已加大关闭按钮点击区域 |
| 关了又马上弹出 | **Frequency** 设为关闭后 **7 天** 不再显示，不要设「每页都显示」 |
| 登录 WP 后台测试 | 先 **退出登录** 或用 **隐身窗口** 测（管理员会话有时行为不同） |
| 遮罩还在、弹窗没了 | 刷新页面；Purge cache；或暂时 Disable 表单再 Enable |

## 八、故障排查

| 现象 | 处理 |
|------|------|
| 没有弹窗 | Omnisend 后台 Form 是否 **Enabled**；是否已 Connect；广告拦截 / 隐身模式 |
| 插件菜单无 Omnisend | 确认 `omnisend-connect` 已激活 |
| 邮件没收到 | 查 Automation 是否启用；垃圾邮件箱；Omnisend 发信域名验证 |
| 优惠码无效 | Marketing → Coupons → `WELCOME10` 是否 Published |

---

## 八、线上已执行（Agent）

```bash
wp plugin install omnisend-connect --activate
# Coupon WELCOME10 created (post ID 31)
```

连接 Omnisend 账号与弹窗样式需你在 **WP 后台 + app.omnisend.com** 完成。
