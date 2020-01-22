ボケマリック機構
===

モダンなAPI実装を目指したPHP製CMSです。
Twig(PythonのJinjaと同じ文法のテンプレートエンジン)用のHTMLを用いてテーマを作成でき、
Markdownで記事を書くことができます。

設定ファイルを記述することでテーマやルーティングをページごとに細かく設定できる柔軟さが作者なりの売りです。

## インストール方法

### Composerを使う

```bash
# hogeディレクトリにプロジェクトを作成します
composer create-project boke0/boke0ick_mecha hoge
```

### GitとComposerを使う

```bash
git clone https://github.com/boke0/boke0ick_mecha.git
cd boke0ick_mecha
composer install
```

## テーマのインストール

themes/ディレクトリにディレクトリごとコピーしてください。

## プラグインのインストール

plugins/
ディレクトリにプラグインのディレクトリごとコピーしてください。
このとき、
plugins/<プラグイン名>/__construct.php
というパスに
\_\_construct.php
が設置されるようにしてください。

## テーマ作成

テーマは
themes/
ディレクトリ内にディレクトリを作成し、
その中にtwig用のHTMLテンプレートファイルを設置するだけで作成することができます。

### テーマの公開

テーマのソースコードを公開したい場合は、
テーマのディレクトリごとGitで管理してしまうことをおすすめします。
そうすることで、利用者は
themes/
ディレクトリ下でリポジトリをcloneするだけで利用を始めることができます。

## プラグイン開発

プラグインは
plugins/
ディレクトリ内にディレクトリを作成し、
その中にPHPファイルを設置することで作成できます。

プラグインのディレクトリ内には、最低限
\_\_construct.php
を設置する必要があります。ボケマリック機構本体からはこのファイルが呼び出され、
このファイルに記述されている通りにプラグインの挙動を設定します。

デフォルトで設置されているSamplePluginを例に解説します。

```PHP
<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;

use \Boke0\Mechanism\Api\Plugin;

$plugin=new Plugin();
$plugin->endpoint(SampleEndpoint::class);
$plugin->templateExtension(SampleTemplateExtension::class);

return $plugin;
```

プラグインの名前空間はPsr-4に従い、
Boke0\Mechanism\Plugins\<プラグイン名>
としてください。

このプログラム内で一つの
Boke0\Mechanism\Api\Plugin
クラスを継承したオブジェクトを作成し、
拡張機能を設定してオブジェクトを返却してください。

### API使用

ボケマリック機構から提供されているAPIを紹介します。初版では、

- 独自のエンドポイントの作成
- テンプレートエンジンの拡張

の2つの機能が搭載されています。

#### エンドポイントの作成

任意のURLにおいて特定のクラスメソッドを実行するように設定することができます。

```PHP
<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;
use \Boke0\Mechanism\Api\Endpoint;

/**
 * @path /sampleendpoint
 */
class SampleEndpoint extends Endpoint{
    public function handle($req,$args){
        $res=$this->createResponse();
        $body=$res->getBody();
        $body->write("Hello world!");
        return $res->withBody($body);
    }
}
```
エンドポイントを作成するときは、
Boke0\Mechanism\Api\Endpoint
を継承したクラスを定義してください。

例のように、ドキュメントコメントでパスを設定しましょう。
パスだけでなく、リクエスト時のメソッドも設定することができます。
```
/**
 * @path /sampleendpoint/insert
 * @method POST
 */
```

クラスはPSR-15で定められているRequestHandlerインターフェースを実装している必要があります。
EndpointクラスにはPSR-7準拠のレスポンスオブジェクトを生成するメソッド
Endpoint::createResponse($status_code,$reason);
が定義されているので、これを呼び出してレスポンスを返却してください。

#### テンプレートエンジンの拡張

ボケマリック機構内でレンダリング時に使用されるTwigに対し、
拡張機能（TwigExtension）を設定できます。
Twigでサポートされている拡張方法であればここでも適用することができます。
以下に、
```
{{ sample() }}
```
と実行すると
```
sample function this is
```
と表示されるテンプレートエンジン拡張の例を示します。

```PHP
<?php

namespace Boke0\Mechanism\Plugins\SamplePlugin;
use \Boke0\Mechanism\Api\TemplateExtension;

class SampleTemplateExtension extends TemplateExtension{
    public function getFunctions(){
        return [
            new \Twig\TwigFunction("sample",function(){
                echo "sample function this is";
            })
        ];
    }
}
```

Twigの拡張を行うときは、
Boke0\Mechanism\Api\TemplateExtension
クラスを継承したクラスを定義してください。

### プラグインの公開

テーマのソースコードを公開したい場合は、
こちらもテーマと同様にGitを使用することをおすすめします。


