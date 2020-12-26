import React from 'react';

// import { Container } from './styles';

export interface Post {
  id: number;
  image: string;
  description: string;
  userName: string;
}

export interface PostsProps {
  posts: Post[];
}

const Posts: React.FC<PostsProps> = ({ posts }: PostsProps) => {
  return (
    <>
      <div>PostList</div>
      <ul title="postlist">
        {posts === [] ? (
          <li>Loading...</li>
        ) : (
          posts.map(({ id, image, description, userName }: Post) => (
            <li key={id} data-testid="post-infos">
              <img src={image} alt={description} />
              <span>{userName}</span>
              <p>{description}</p>
            </li>
          ))
        )}
      </ul>
    </>
  );
};

export default Posts;
