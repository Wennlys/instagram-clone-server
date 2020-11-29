import React from 'react';

// import { Container } from './styles';

export interface IPost {
  id: number;
  image: string;
  description: string;
  userName: string;
}

export interface PostsProps {
  posts: IPost[];
}

const Posts: React.FC<PostsProps> = ({ posts }: PostsProps) => {
  return (
    <>
      <div>PostList</div>
      <ul title="postlist">
        {posts.map(({ id, image, description, userName }) => (
          <li key={id} data-testid="post-infos">
            {id}
          </li>
        ))}
      </ul>
    </>
  );
};

export default Posts;
