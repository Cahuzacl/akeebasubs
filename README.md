# Akeeba Subscriptions

This branch contains the current, in-development version of Akeeba Subscriptions. This is currently work in progress. Expect many things to change in the first few weeks of 2014.

## Build instructions

### Prerequisites

In order to build the installation packages of this component you will need to have the following tools:

* A command line environment. Using Bash under Linux / Mac OS X works best. On Windows you will need to run most tools through an elevated privileges (administrator) command prompt on an NTFS filesystem due to the use of symlinks.
* A PHP CLI binary in your path
* Command line Git executables
* PEAR and Phing installed, with the Net_FTP and VersionControl_SVN PEAR packages installed
* libxml and libsxlt command-line tools, only if you intend on building the documentation PDF files

You will also need the following path structure inside a folder on your system

* **akeebasubs** This repository. We will refer to this as the MAIN directory
* **buildfiles** [Akeeba Build Tools](https://github.com/akeeba/buildfiles)
* **fof** [Framework on Framework](https://github.com/akeeba/fof)

You must use the exact folder names specified here.

### Initialising the repository

All of the following commands are to be run from the MAIN directory.

1. You will first need to do the initial link with Akeeba Build Tools, running the following command

		php ../buildfiles/tools/link.php `pwd`
		
	or, on Windows:
	
		php ../buildfiles/tools/link.php %CD%
		
2. After the initial linking takes place, go inside the build directory:

		cd build
		
	and run the Phing task called link:
	
		phing link
		
	If you are on Windows make sure that you are running an elevated command prompt (run cmd.exe as Administrator)
	
### Useful Phing tasks

All of the following commands are to be run build directory inside the MAIN directory.

#### Symlinking to a Joomla! installation
This will create symlinks and hardlinks from your working directory to a locally installed Joomla! site. Any changes you perform to the repository files will be instantly reflected to the site, without the need to deploy your changes.

	phing relink -Dsite=/path/to/site/root
	
or, on Windows:

	phing relink -Dsite=c:\path\to\site\root
	
**Examples**

	phing relink -Dsite=/var/www/html/joomla
	
or, on Windows:
	
	phing relink -Dsite=c:\xampp\htdocs\joomla

#### Relinking internal files

This is required after every major upgrade in the component and/or when new plugins and modules are installed. It will create symlinks from the various external repositories to the MAIN directory.

	phing link
	
#### Creating a dev release installation package

This creates the installable ZIP packages of the component inside the MAIN/release directory.

	phing git
	
#### Build the documentation in PDF format

This builds the documentation in PDF format using the DocBook XML sources found in the documentation directory.

	phing documentation
	
## Collaboration

If you have found a bug you can submit your patch by doing a Pull Request on GitHub.