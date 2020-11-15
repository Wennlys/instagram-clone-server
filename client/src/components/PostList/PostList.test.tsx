import { screen } from '@testing-library/dom';
import React from 'react';
import Posts from '.';
import { postsResponseMock, renderWithRouter } from '../../utils/test.utils';

describe('Post component', () => {
  test('component rendering', () => {
    const loadPosts = jest.fn().mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Posts loadPosts={loadPosts} />);
    expect(screen.getByText(/PostList/i)).toBeInTheDocument();
  });

  it('calls loadPosts function', () => {
    const loadPosts = jest.fn().mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Posts loadPosts={loadPosts} />);
    expect(loadPosts).toHaveBeenCalled();
  });
});
