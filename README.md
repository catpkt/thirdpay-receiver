第三方支付使用者
================================

# 概述

配合 catpkt/thirdpay-provider 实现通用第三方支付集成。

* catpkt/thirdpay-provider 用于提供统一接口的第三方支付微服务
* catpkt/thirdpay-receiver *用于调用这些微服务*


# 使用

1. 在类中引入trait `\CatPKT\ThirdPayReceiver\TThirdPayReceiver`。
2. 实现抽象方法 `getEncryptor` 提供加密器。
2. 实现抽象方法　`getApiUri`, 提供所要调用微服务的接口链接。
3. 实现抽象方法 `asyncCallback`, 实现支付成功后的异步回调。
4. 设定路由，将某个目录指向 `handle` 方法，用于接收回调。
5. 调用类中的 `createPay` 方法发起支付。
