# Как создавать свои рейтинги?

Когда я создавал данный плагин, я изначально не планировал использовать базу данных и создавать лишних ненужных таблиц. Все реализованно на файлах.

И так. Начнем.

1. Создаем свой плагин с помощью CGI команды `php cmd.php xf-addon:create`.
2. Заходим в папку созданного дополнения и создаем папку `Rating`. 
В папке `Rating` будут расположены все наши рейтинги.
3. Создаем тестовый рейтинг, скажем `Рейтинг сообщений` (Конечно такой уже есть, но для примера можно). Для этого создаем файл `TestUserMessage.php` с следующим содержимым (Весь код описан в комментариях):
```php
<?php

namespace ПространствоВашегоПлагина\Rating;

class TestUserMessage extends AbstractRating //Подключаем абстрактный класс.
{
	/**
	 * Данная функция говорит нам как будет называтся наш рейтинг.
	 */
	public function getTitle()
	{
		return \XF::phrase('sxfr_usermessage');
	}
	
	/**
	 * Данная функция дает нам иконку, которая будет отображатся в списке рейтингов.
	 * Она может быть как иконкой Font Awesome, так и изображеним.
	 * Что бы указать изображение, нужно указать полную ссылку до её местоположения.
	 */
	public function getIcon()
	{
		return 'fa-comments-o';
	}
	
	/**
	 * Тут осуществляется генерация рейтинга.
	 */
	public function render()
	{
		// Спрашиваем лимит записей.
		$limit = $this->getLimit();
		
		// Получаем массив пользователей, сортируем его по сообщениям и отризаем по лимиту.
		$users = $this->finder('XF:User')->order('message_count', 'DESC')->limit($limit)->fetch();
		
		// Тут мы сдвигаем массив на 1 элемент вправо, иначе рейтинг будет отображатся начиная с нуля.
		// Можно опустить этот момент и прабавлять 1 к ключу в шаблоне.
		$list = array_values($users->toArray());
		$newList = [];
		
		for ($i = 0; $i < count($list); $i++)
		{
			$newList[$i + 1] = $list[$i];
		}
		
		// Создаем массив с параметрами для шаблона.
		$viewParams = [
			'users' => $newList
		];
		
		// Возвращаем название шаблона и вставляем в него наш массив переменных для шаблона.
		// Шаблон у нас называется [test_user_message]
		return $this->renderer('test_user_message', $viewParams);
	}
}
```
На этом мы закончили с обработчиком.
4. Теперь нам нужно зарегистрировать наш обработчик. Создаем адресс `XF\Repository` в нашей папке плагина. В конечной папке создаем файл `Rating.php` со следующим содержимым:
```php
<?php

namespace ПространствоВашегоПлагина\XF\Repository;

class Rating extends XFCP_Rating
{
	public function getTypes($types = [])
	{
		//Вот таким способом мы регистрируем наш обработчик.
		$types['test_user_message'] = 'ПространствоВашегоПлагина:TestUserMessage';
		
		return $types;
	}
}
```
Теперь переходим в панель управления администратора, на страницу `Расширения классов` и создаем расширение, где `Название базового класса` указываем `\SXFRating\Repository\Rating`, а где `Название класса расширения` указываем `ПространствоВашегоПлагина\XF\Repository\Rating`, выбираем шан плагин и сохраняем.
6. Теперь нам нужно создать шаблон `test_user_message`, который мы указывали в обработчике. Не буду описывать его содержимое, но думаю поймете.
```html
<xf:datalist>
	<xf:datarow rowtype="subsection" rowclass="dataList-row--noHover">
		<xf:cell>#</xf:cell>
		<xf:cell>{{ phrase('name') }}</xf:cell>
		<xf:cell>{{ phrase('messages') }}</xf:cell>
	</xf:datarow>
	
	<xf:foreach loop="{$users}" key="$key" value="$user">
		<xf:datarow rowclass="rating-message--{$key} rating-top--{$key} {{ $xf.visitor.user_id == $user.user_id ? 'rating-top--visitor' : '' }}">
			<xf:cell>{$key}</xf:cell>
			<xf:cell>
				<xf:username user="{$user}" />
			</xf:cell>
			<xf:cell>
				<a href="{{ link('search/member', null, {'user_id': $user.user_id}) }}">{$user.message_count|number}</a>
			</xf:cell>
		</xf:datarow>
	</xf:foreach>
</xf:datalist>
```
7. Если все сделали правильно, то в настройках `[SXF] Rating` появится наш рейтинг с именем который вы когда-то указали в `getTitle()`. Ставим галочку, тем самым включая его. Теперь он так же будет отображен в списке рейтингов.

Спасибо за внимание!
