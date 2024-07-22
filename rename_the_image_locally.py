import os
import zlib

def calculate_crc32(file_path):
    with open(file_path, 'rb') as f:
        file_data = f.read()
    return format(zlib.crc32(file_data) & 0xFFFFFFFF, '08x')

def rename_files_and_save_to_txt(directory, base_url, output_file):
    with open(output_file, 'w', encoding='utf-8') as txt_file:
        for root, _, files in os.walk(directory):
            for file_name in files:
                file_path = os.path.join(root, file_name)
                # 计算CRC32
                crc32_name = calculate_crc32(file_path) + os.path.splitext(file_name)[1]
                new_file_path = os.path.join(root, crc32_name)

                # 如果目标文件名已存在，则添加一个唯一标识符
                counter = 1
                while os.path.exists(new_file_path):
                    crc32_name = f"{calculate_crc32(file_path)}_{counter}{os.path.splitext(file_name)[1]}"
                    new_file_path = os.path.join(root, crc32_name)
                    counter += 1

                # 重命名文件
                os.rename(file_path, new_file_path)
                # 拼接网址和文件名
                full_url = os.path.join(base_url, crc32_name)
                # 写入TXT文件
                txt_file.write(full_url + '\n')
                print(f'Renamed {file_path} to {new_file_path}')
                print(f'Saved URL: {full_url}')

# 使用示例
directory = 'C:\ppt\mlcmm\image'
base_url = 'https://you_url/wallpaper/'#你的云储存链接前缀路径
output_file = 'output.txt'

rename_files_and_save_to_txt(directory, base_url, output_file)
