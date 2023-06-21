# Additional Image Sizes Manager

WordPress plugin for creating and managing additional image sizes.

## Features

- The plugin provides a user-friendly form to add unlimited custom image sizes, including fields for name, maximum width, maximum height, and description.
- After regenerating images, the newly added image sizes are available in the image size dropdown in post and page editors.
- Users can export and import their additional image sizes for backup or transferring between environments.
- Additional image sizes are listed on the Media Settings page so size settings can be viewed can be viewed in one place.
- Managing settings using a plugin ensures the settings will persist if ever changing WordPress themes.

## Installation

Follow these steps to install the Additional Image Sizes Manager plugin:

1. Download the plugin ZIP file from the [GitHub repository](https://github.com/danpoynor/additional-image-sizes-manager).
2. Log in to your WordPress administration area.
3. Navigate to **Plugins > Add New**.
4. Click the **Upload Plugin** button at the top of the page.
5. Choose the downloaded ZIP file and click **Install Now**.
6. After installation, click the **Activate Plugin** button.

## Usage

Once you activate the plugin in your WordPress administration area, you will find an Additional Image Sizes Manager section under Settings > Media. Additionally, a new admin page titled "Additional Image Sizes Manager" (AISM) will be available under Settings.

On the AISM page, you can easily add, edit, delete, import, and export custom image sizes.

After adding or modifying image sizes, it is necessary to regenerate the images using a plugin such as [Regenerate Thumbnails](https://wordpress.org/plugins/regenerate-thumbnails/) or the [WordPress Command Line Interface](https://make.wordpress.org/cli/handbook/) by running the command `wp media regenerate`.

After regenerating the images, you should see the custom image sizes listed in the image size dropdown when editing posts and pages using either the Gutenberg block editor or the Classic editor.

To facilitate migration between environments, users can export their custom additional image sizes as a JSON file and import it on another system. Please note that importing and exporting do not check for duplicate entries or resize previously uploaded Media Library images.

## Uninstall

If you choose to deactivate and delete the plugin, the custom additional image sizes option (`additional_image_sizes_manager`) will be removed from the database. This occurs when you click the "Deactivate" link on the Plugin page, followed by the "Delete" link.

## Screenshots

### Additional Image Sizes Manager Settings

![01-additional-image-sizes-manager-settings-page-v2](https://github.com/danpoynor/additional-image-sizes-manager/assets/764270/7df498a0-7c29-4c59-891b-f62a209e5b59)

### Additional Image Sizes section on Media Settings page

![02-additional-image-sizes-manager-media-settings-v2](https://github.com/danpoynor/additional-image-sizes-manager/assets/764270/6563e2d5-89e5-477a-ba21-4d33e00deeb3)

### Select generated custom image size in Gutenberg editor

![03-additional-image-sizes-manager-gutenberg-block-editor](https://github.com/danpoynor/additional-image-sizes-manager/assets/764270/84e13d30-3806-49cf-a8da-784b5719c16d)

### Select generated custom image size in Classic editor

![04-additional-image-sizes-manager-classic-editor](https://github.com/danpoynor/additional-image-sizes-manager/assets/764270/f34d105e-c71c-4a7c-a4fc-cb5292515f66)

## Known Bugs

- None currently.

## Potential To-Do List

- Make the columns in the additional image sizes lists sortable for better organization.
- Implement dismissible notifications to provide users with feedback on various actions, such as successful additions, updates, or deletions.
- Enhance error handling and provide informative feedback in case of any activation or configuration issues.
- Consider separating PHP logic and HTML into individual files to improve maintainability.
- Consider implementing support for cropped images.
- Include support for additional languages.
- Test the plugin for compatibility with other popular plugins to avoid conflicts.
- Develop unit tests to ensure the stability and reliability of the plugin.
- Submit the plugin to the official [WordPress Plugin Directory](https://wordpress.org/plugins/).
