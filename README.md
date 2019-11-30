# Utils
Tools for development and unit testing

## Installation

composer require --dev kirillzhirov/utils

## Usage

```
\Utils\Reflection::invokeProtectedMethod($object, 'methodName');
```
```
\Utils\Reflection::getObjectWithoutConstructor($className);
\Utils\Reflection::getObjectWithoutConstructor($className, 'propertyName', 'propertyValue');
```
```
\Utils\Reflection::getProtectedProperty($object, 'propertyName');
```
```
\Utils\Reflection::setProtectedProperty($object, 'propertyName', 'propertyValue');
```
```
\Utils\Reflection::getPrivateProperty($object, 'propertyName');
```
```
\Utils\Reflection::setPrivateProperty($object, 'propertyName', 'propertyValue');
```
