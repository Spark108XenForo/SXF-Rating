# How to create your ratings?

When I created this plugin, I initially did not plan to use the database and create unnecessary unnecessary tables. Everything is implemented on files.

So. Let's start.

1. Create your plugin using the CGI command `php cmd.php xf-addon: create`.
2. Go to the folder of the created add-on and create the folder `Rating`.
In the folder `Rating` all our ratings will be located.
3. Create a test rating, say `Message rating` (Of course, this is already there, but for example, you can). To do this, create a file `TestUserMessage.php` with the following content (All code is described in the comments):
```php
<?php

namespace SpaceofyourAddOn\Rating;

class TestUserMessage extends AbstractRating // We connect the abstract class.
{
	/**
	 * This function tells us how our rating will be called.
	 */
	public function getTitle()
	{
		return \XF::phrase('sxfr_usermessage');
	}

	/**
	 * This function gives us an icon that will be displayed in the list of ratings.
	 * It can be both Font Awesome icon, and image.
	 * To specify an image, you need to specify the full link to its location.
	 */
	public function getIcon()
	{
		return 'fa-comments-o';
	}

	/**
	 * Here the rating is generated.
	 */
	public function render()
	{
		// We ask the limit of records.
		$limit = $this->getLimit();

		// Get an array of users, sort it by messages and render it by limit.
		$users = $this->finder('XF: User')->order('message_count', 'DESC')->limit($limit)->fetch();

		// Here we shift the array by 1 element to the right, otherwise the rating will be displayed starting from zero.
		// You can omit this moment and add 1 to the key in the template.
		$list = array_values ​($users->toArray());
		$newList = [];

		for ($i = 0; $i < count($list); $i++)
		{
			$newList [$i + 1] = $list[$i];
		}

		// Create an array with the parameters for the template.
		$viewParams = [
			'users' => $newList
		];

		// Return the name of the template and insert into it our array of variables for the template.
		// The template is called [test_user_message]
		return $this->renderer('test_user_message', $viewParams);
	}
}
```
This concludes with the handler.
4. Now we need to register our handler. Create the address `XF \ Repository` in our plugin folder. In the destination folder, create the file `Rating.php` with the following content:
```php
<?php

namespace SpaceofYourAddOn\XF\Repository;

class Rating extends XFCP_Rating
{
	public function getTypes($types = [])
	{
		// That's how we register our handler.
		$types['test_user_message'] = 'TheSpaceofYourAddOn:TestUserMessage';

		return $types;
	}
}
```
Now go to the administrator control panel, to the page `Extensions of classes` and create an extension, where` Base class name` is specified `\SXFRating\Repository\Rating`, and where` Expansion class name` is specified `SpaceofYourAddOn\XF\Repository\Rating` , select the shan plug and save.
6. Now we need to create a template `test_user_message`, which we specified in the handler. I will not describe its contents, but I think you will understand.
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
7. If everything is done correctly, then in the settings of `[SXF] Rating` our rating will appear with the name that you once specified in` getTitle()`. Put a tick, thereby including it. Now it will also be displayed in the list of ratings.

Thank you for attention!
