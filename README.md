# 課題8 - BookmarksApp

## ①課題内容
このアプリケーションでは、ユーザーが本をお気に入りとして追加し、検索、編集、削除、カテゴリや著者ごとにフィルタリングできるブックマーク管理システムを作成しました。

主な機能は以下の通りです：
- 本の追加・編集・削除機能
- タイトル、著者、カテゴリでの検索機能
- ページネーション対応のリスト表示
- モーダルを使用した削除確認

## ②工夫した点・こだわった点

### **機能面**
- **柔軟な検索機能**:
  タイトル、著者、カテゴリの複合条件で検索可能。
- **カテゴリ・著者リストの自動生成**:
  データベースに保存された内容から動的にカテゴリと著者リストを生成。

### **デザイン**
- **レスポンシブ対応**:
  スマートフォンやタブレットでも使いやすいレイアウトを採用。
- **操作性**:
  ボタンやリンクのホバー時に色を変えるなど、操作を視覚的にフィードバック。

## ③やり残したこと
- **ユーザー認証機能**:
  現時点では全ユーザーが同じデータベースを共有しています。ログイン機能を追加し、ユーザーごとにブックマークを管理できるようにしたいです。
- **ブックカバー表示**:
  Google Books APIなどを使用し、書籍の表紙画像を表示する機能を追加したかったです。

## ④その他（感想、シェアしたいことなんでも）
  フロントエンドには現在CSSと基本的なJavaScriptを使用していますが、ReactやVue.jsなどのフレームワークを使いたい。