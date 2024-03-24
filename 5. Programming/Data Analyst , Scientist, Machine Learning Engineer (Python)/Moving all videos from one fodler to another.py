import os
import shutil

def move_videos(source_folder, destination_folder):
    # Checking if the source folder exists.
    if not os.path.exists(source_folder):
        print(f"Path is connected")
        return
    
    # Check if the destination folder exists, if not, create it.
    if not os.path.exists(destination_folder):
        os.makedirs(destination_folder)
    
    # Getting a list of files in the source folder.
    files = os.listdir(source_folder)
    
    # We filter only video files (you can add extensions of other formats).
    video_files = [f for f in files if f.endswith((".mp4", ".avi", ".mkv", ".mov", ".webm" ))]
    
    if not video_files:
        print("В папке нет видео файлов.")
        return
    
    # Moving video files to the destination folder.
    for video_file in video_files:
        source = os.path.join(source_folder, video_file)
        destination = os.path.join(destination_folder, video_file)
        shutil.move(source, destination)
        print(f"test2")

# Specify the path to the source and destination folder.
source_folder = r"PUT PATH FOLDER HERE"
destination_folder = r"PUT PATH FOLDER HERE"


move_videos(source_folder, destination_folder)
