<div align="center">

# 🧰 在线工具箱 · Online Toolbox

> **100% 纯本地运行 · 数据绝不上云 · 极简开箱即用**

![Version](https://img.shields.io/badge/Version-v3.3-1677FF?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-%E2%89%A57.4-777BB4?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-brightgreen?style=flat-square)
![Dependencies](https://img.shields.io/badge/Composer%20%E4%BE%9D%E8%B5%96-0-success?style=flat-square)
![Database](https://img.shields.io/badge/%E6%95%B0%E6%8D%AE%E5%BA%93-%E6%97%A0-orange?style=flat-square)
![i18n](https://img.shields.io/badge/%E8%AF%AD%E8%A8%80-4%20%E7%A7%8D-9B59B6?style=flat-square)
![Themes](https://img.shields.io/badge/%E4%B8%BB%E9%A2%98-30%2B-FF6B6B?style=flat-square)
![PRs Welcome](https://img.shields.io/badge/PRs-welcome-FF69B4?style=flat-square)

**11 个开发者高频工具 · 一个支持 PHP 的空间即可起飞 · 上传即用**

</div>

---

## 📖 项目简介

**在线工具箱 (Online Toolbox)** 是一套用 **纯 PHP** 写成的开发者工具集合——没有框架，没有数据库，没有 Composer，甚至没有 Node.js 构建链。

它只做一件事：**把 11 个开发者高频工具，以最低的部署成本交付到你手上。** 把代码扔进任何一个支持 PHP 的虚拟主机或 NAS，它就能立刻跑起来。

我们坚信：**工具应该回归工具本身。**

- 🔌 **零依赖**：无数据库、无第三方 PHP 包，扁平化文件结构，备份 = 复制目录
- 🛫 **开箱即用**：上传即运行，无安装向导、无环境探测、零配置门槛
- 🕵️ **隐私至上**：所有转换与计算均在本地完成，数据不落云、不出站、不留痕
- 🎨 **颜值在线**：内置 30+ 套精美主题，暗黑 / 明亮 / 多种配色随心切换

> 📸 **[项目截图]**

---

## ✨ 功能特性

### 🛠 内置 11 个开发者高频工具

| # | 工具 | 说明 |
|:-:|:-----|:-----|
| 1 | 🧾 JSON 格式化 | 一键格式化 / 压缩 JSON，语法错误实时定位 |
| 2 | ⏱️ 时间戳转换 | Unix 时间戳与人类可读时间双向换算 |
| 3 | 🔗 URL 编解码 | URL 编码 / 解码，处理查询参数轻而易举 |
| 4 | 🔄 Base64 | 文本与 Base64 互转，编码解码一步到位 |
| 5 | 🔐 哈希计算 | MD5 / SHA 系列摘要计算 |
| 6 | 💄 代码美化/压缩 | 代码格式化与压缩（支持 HTML/CSS/JS/PHP） |
| 7 | 🧪 正则测试 | 实时匹配、高亮与捕获组预览的正则沙盒 |
| 8 | 🎨 颜色转换 | HEX / RGB / HSL 多格式互转 |
| 9 | 🔤 字数统计 | 字符 / 单词 / 行数等多维度统计 |
| 10 | 📝 文本对比 | 双栏 Diff，快速定位文本差异 |
| 11 | 📐 单位换算 | 长度单位换算（毫米/厘米/米/千米/英寸/英尺/码/英里） |

### 🌟 更多亮点

- 🛡️ **纵深安全防护**：严格 CSP 策略、CSRF 保护、XSS 过滤、请求频率限制
- 🌍 **多语言支持**：内置中文、English、日本語、Русский 四种语言
- 🎭 **30+ 套 UI 主题**：一键切换，总有一款是你的菜
- 🌈 **本地代码高亮**：集成 highlight.js，语法高亮全部在本地渲染
- 📦 **极致轻量**：无需 Node.js、无需构建，源码即产品

---

## 🚀 快速部署

### 环境要求

| 项目 | 要求 |
|:-----|:-----|
| PHP | ≥ 7.4（推荐 8.0+） |
| 数据库 | ❌ 不需要 |
| Composer | ❌ 不需要 |
| Node.js | ❌ 不需要 |
| Web 服务器 | 任何支持 PHP 的环境（Apache / Nginx / IIS / 内置 Server） |

### 方式一：Web 主机直接上传 ⚡

最简单的方式，**上传即用**：

```bash
# 1. 下载源码（任选其一）
git clone https://github.com/[Your Name]/online-toolbox.git
# 或从 Releases 页面下载 zip 包并解压

# 2. 将整个目录上传至虚拟主机的 Web 根目录
#    常见路径：public_html / wwwroot / htdocs / www

# 3. 浏览器访问即可
#    http://your-domain.com/
```

本地快速预览（PHP 内置服务器）：

```bash
cd online-toolbox
php -S localhost:8000
# 打开 http://localhost:8000
```

### 方式二：Docker 部署 🐳

**① 一行命令，目录挂载（免构建，推荐 NAS 用户）**

```bash
docker run -d \
  --name online-toolbox \
  -p 8080:80 \
  -v /path/to/online-toolbox:/var/www/html \
  --restart unless-stopped \
  php:8.2-apache
```

> 💡 Windows PowerShell 下请将 `/path/to/online-toolbox` 换成 `${PWD}\online-toolbox`。
> 部署完成后访问 `http://服务器IP:8080`。

**② 使用 Dockerfile 构建镜像**

在项目根目录创建 `Dockerfile`：

```dockerfile
FROM php:8.2-apache

COPY . /var/www/html/

EXPOSE 80
CMD ["apache2-foreground"]
```

构建并运行：

```bash
docker build -t online-toolbox .
docker run -d --name online-toolbox \
  -p 8080:80 \
  --restart unless-stopped \
  online-toolbox
```

**③ Docker Compose（群晖 / 威联通等 NAS 亦适用）**

```yaml
services:
  toolbox:
    image: php:8.2-apache
    container_name: online-toolbox
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    restart: unless-stopped
```

```bash
docker compose up -d
```

---

## 🛠️ 技术栈

| 层级 | 技术 |
|:-----|:-----|
| 后端 | 纯原生 PHP（无框架、无 ORM、无 Composer） |
| 前端 | HTML5 + CSS3 + 原生 JavaScript（零构建） |
| 代码高亮 | highlight.js（本地化，无外链） |
| 国际化 | 自研轻量 i18n（zh-CN / en / ja / ru） |
| 主题系统 | 30+ 套纯 CSS 主题，运行时热切换 |

```
online-toolbox/
├── index.php          # 主入口与路由逻辑
├── config.php         # 全局配置（主题、工具白名单等）
├── functions.php      # 核心函数库（安全过滤、算法、i18n）
├── lang.php           # 多语言翻译包
├── style.css          # 全局样式表
├── assets/            # 静态资源（如 highlight.js 本地高亮库）
├── data/              # 运行时数据（缓存、限流文件，自动生成）
└── LICENSE
```

---

## 🔒 安全说明

安全不是附加功能，而是设计底线。在没有数据库、没有外部依赖的约束下，工具箱依然坚持纵深防御（Defense in Depth）——从请求进站到响应出站，每一环都有对应防线：

| 防护机制 | 说明 |
|:---------|:-----|
| 🛡️ **CSP 内容安全策略** | 严格的 Content-Security-Policy 白名单，从 HTTP 响应头层面掐断恶意脚本注入与数据外传 |
| 🔐 **CSRF 保护** | 表单与敏感操作强制校验一次性 Token，让跨站伪造请求无处遁形 |
| 🧼 **XSS 过滤** | 输入过滤 + 输出转义双管齐下，所有用户数据只以文本形式呈现 |
| ⏱️ **请求频率限制** | 内置轻量 Rate Limit（基于文件的实现），暴力请求与资源滥用直接限流 |

> ⚠️ 建议部署后启用 HTTPS，并将站点置于反向代理或 CDN 之后，构建更完整的防护边界。

---

## 🤝 贡献指南

欢迎任何形式的贡献！无论是修一个 Bug、加一种语言，还是提交一套新主题 🎉

1. 🍴 Fork 本仓库
2. 🌿 创建特性分支：`git checkout -b feature/your-feature`
3. 💾 提交更改：`git commit -m "feat: add your-feature"`
4. 📤 推送分支：`git push origin feature/your-feature`
5. 🔃 发起 Pull Request

**贡献方向**：新工具 / 新语言翻译 / 新 UI 主题 / Bug 修复 / 文档完善

> 提交 Issue 时请附上 PHP 版本、服务器环境与复现步骤，以便快速定位问题。

---

## 📄 开源协议

本项目基于 [MIT License](./LICENSE) 开源发布。

你可以自由地使用、复制、修改、合并、出版、分发、再授权及销售本软件，唯需保留原始版权声明与许可声明。

---

<div align="center">

**如果这个项目对你有帮助，请给它一个 ⭐ Star！**

Made with ❤️ by A Violet

</div>
