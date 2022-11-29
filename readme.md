# Group F year 1 period 1 

## Project OUI

A NHLStenden student project in cooparation with an imaginary dutch newspaper that is represented by one of our teachers Bart Oerlemans.

### Group members:
* Yohan Lozanov (Leader) Email: yohan.lozanov@student.nhlstenden.com
* Joey Krämer (Co-leader) Email: joey.kramer@student.nhlstenden.com
* Beyza Ölmez  Email: beyza.olmez@student.nhlstenden.com
* Sebastian Güntzel Email: sebastian.guntzel@student.nhlstenden.com
* Nikolay Minins Email: nikolay.minins@student.nhlstenden.com
* Timothy Adewale Email: :adewale.adewale@student.nhlstenden.com
* Stefan Tasca Email: stefan.tasca@student.nhlstenden.com

# Git tutorial
## If you want to use git make sure you
* Open your terminal/console 
### If you didn't have folder yet use in console
* Go to the folder where you want to have a folder of git repository using (cd /Path)
* Then write git clone https://github.com/NidLip/Newspaper-1CT-1F.git (or use SSH key)
* Navigate to the folder where you have the project files cd GroupF-term1
* Switch to your branch
* Pull
* Write your code
* Commit, pull, push, create pull request
* Repeat 4.-6. until done
## To switch to you branch
* git checkout YOUR_BRANCH_HERE (keep in mind this will discard all your local changes)
## To commit your changes and push your files
* git add . (This command adds all the files to git)
* git commit -m "YOUR_MESSAGE_HERE" (message is necessary, always give information what you have done)
* git pull (make sure you have the latest version)
* git push (push the commits to GitHub)
## To get the newest files from git
* git pull
## To create a pull request
* Go to the GitHub repository
* Click pull requests
* New pull request
* Click on your branch
* Write a title, a short comment and add Yohan and Joey as reviewers
* Wait for feedback
## To revert your push
* Find the hash of you commit from GitHub
* git revert GIT_COMMIT_HASH
* git commit -m "MESSAGE"
* git push
## To sync your branch to master
* Make sure you are in your branch
* git fetch origin
* git merge origin/master
* (optional) fix merge conflicts